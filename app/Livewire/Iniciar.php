<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Despacho;
use App\Models\Contenido;
use App\Models\Saca;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class Iniciar extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $categoria;
    public $ofdestino;
    public $subclase;
    public $nrodespacho;
    public $fechaHoraActual;
    public $perPage = 10;

    protected $rules = [
        'categoria' => 'required|string|max:50',
        'ofdestino' => 'required|string|max:50',
        'subclase' => 'required|string|max:50',
    ];

    protected $cityCodes = [
        'LA PAZ' => 'BOLPZ',
        'TARIJA' => 'BOTJA',
        'POTOSI' => 'BOPOI',
        'PANDO' => 'BOCIJ',
        'COCHABAMBA' => 'BOCBB',
        'ORURO' => 'BOORU',
        'BENI' => 'BOTDD',
        'SUCRE' => 'BOSRE',
        'SANTA CRUZ' => 'BOSRZ',
    ];

    public function crearDespacho()
    {
        $this->validate();

        // Verifica que la oficina seleccionada esté definida
        if (!$this->ofdestino) {
            session()->flash('message', 'Por favor, selecciona una oficina.');
            return;
        }

        // Verificar si ya existe un despacho en estado APERTURA o REAPERTURA con el mismo ofdestino
        $despachoExistente = Despacho::where('ofdestino', $this->ofdestino)
            ->whereIn('estado', ['APERTURA', 'REAPERTURA'])
            ->exists();

        if ($despachoExistente) {
            session()->flash('error', 'No se puede crear un despacho con la misma oficina en estado APERTURA o REAPERTURA.');
            $this->dispatch('closeCreateDespachoModal'); // Cierra el modal
            return;
        }

        // Obtiene el último número de despacho para la oficina seleccionada
        $ultimoDespacho = Despacho::where('ofdestino', $this->ofdestino)->latest('id')->first();
        $ultimoNumero = $ultimoDespacho ? intval($ultimoDespacho->nrodespacho) : 0;

        // Incrementa el número de despacho en +1
        $nuevoNumero = $ultimoNumero + 1;

        // Formatea el número con ceros a la izquierda para obtener el formato 001, 002, etc.
        $this->nrodespacho = str_pad($nuevoNumero, 3, '0', STR_PAD_LEFT);

        // Guarda la fecha y hora actual
        $this->fechaHoraActual = Carbon::now()->format('Y-m-d H:i:s');

        // Cierra el modal de creación y abre el de confirmación
        $this->dispatch('closeCreateDespachoModal');
        $this->dispatch('openConfirmModal');
    }


    public function confirmarGuardarDespacho()
    {
        // Calcula el último dígito del año actual
        $ultimoDigitoAno = substr(Carbon::now()->format('Y'), -1);

        // Obtén el código de la ciudad del usuario logueado
        $userCity = auth()->user()->city;
        $ofremitente = $this->cityCodes[$userCity] ?? 'UNKNOWN'; // Usa 'UNKNOWN' si la ciudad no está en el mapeo

        // Construye el identificador concatenando los valores deseados
        $identificador = $ofremitente . $this->ofdestino . $this->categoria . $this->subclase . $ultimoDigitoAno . $this->nrodespacho;

        // Guarda el despacho en la base de datos con el estado "ABIERTO", el último dígito del año, y el identificador
        Despacho::create([
            'categoria' => $this->categoria,
            'ofdestino' => $this->ofdestino,
            'subclase' => $this->subclase,
            'nrodespacho' => $this->nrodespacho,
            'fecha_hora_creacion' => $this->fechaHoraActual,
            'estado' => 'APERTURA',
            'ano' => $ultimoDigitoAno,
            'identificador' => $identificador,  // Guarda el identificador generado
        ]);

        session()->flash('success', 'Despacho creado exitosamente.');

        // Cierra el modal de confirmación y restablece los campos
        $this->dispatch('closeConfirmModal');
        $this->reset(['categoria', 'ofdestino', 'subclase', 'nrodespacho', 'fechaHoraActual']);
    }
    public function reaperturarDespacho($despachoId)
    {
        // Cambiar el estado de todas las sacas relacionadas a 'APERTURA'
        Saca::where('despacho_id', $despachoId)->update(['estado' => 'APERTURA']);

        // Cambiar el estado del despacho a 'REAPERTURA'
        $despacho = Despacho::findOrFail($despachoId);
        $despacho->update(['estado' => 'REAPERTURA']);

        // Emitir un mensaje de éxito
        session()->flash('message', 'Despacho y todas sus sacas reaperturadas exitosamente.');
    }
    public function expedicionDespacho($despachoId)
    {
        // Mapeo de siglas a nombres de ciudades
        $ciudades = [
            'BOLPZ' => 'LA PAZ',
            'BOTJA' => 'TARIJA',
            'BOPOI' => 'POTOSI',
            'BOCIJ' => 'PANDO',
            'BOCBB' => 'COCHABAMBA',
            'BOORU' => 'ORURO',
            'BOTDD' => 'BENI',
            'BOSRE' => 'SUCRE',
            'BOSRZ' => 'SANTA CRUZ',
        ];
    
        // Obtener la ciudad del usuario como origen
        $ciudadOrigen = auth()->user()->city;
        $siglaOrigen = array_search($ciudadOrigen, $ciudades) ?: $ciudadOrigen;
    
        // Encontrar el despacho y actualizar su estado
        $despacho = Despacho::findOrFail($despachoId);
        $despacho->update(['estado' => 'EXPEDICION']);
    
        // Inicializar los totales
        $totalPeso = $totalPaquetes = 0;
        $nropaquetesro = $nropaquetesbl = 0;
        $sacasm = $listas = $lcao = 0;
        $totalContenidoR = $totalContenidoB = 0;
    
        // Obtener todos los registros de saca relacionados al despacho
        $sacas = Saca::where('despacho_id', $despacho->id)->get();
    
        foreach ($sacas as $saca) {
            $contenido = Contenido::where('saca_id', $saca->id)->get();
    
            foreach ($contenido as $item) {
                // Verificar si tiene contenido en etiquetas rojas o blancas
                if ($item->nropaquetesro > 0) {
                    $totalContenidoR += 1; // Cuenta como 1 si hay contenido en rojas
                }
                if ($item->nropaquetesbl > 0) {
                    $totalContenidoB += 1; // Cuenta como 1 si hay contenido en blancas
                }
    
                // Sumar los valores de nropaquetesro y nropaquetesbl
                $nropaquetesro += $item->nropaquetesro;
                $nropaquetesbl += $item->nropaquetesbl;
    
                // Sumar otros campos
                $sacasm += $item->sacasm;
                $listas += $item->listas;
    
                // Acumular el total de paquetes
                $totalPaquetes += $item->nropaquetesro + $item->nropaquetesbl;
            }
        }
    
        // Calcular el total general basado en contenidos rojas, blancas y sacas
        $totalContenido = $totalContenidoR + $totalContenidoB + $sacasm;
    
        // Convertir la sigla de ofdestino a nombre de ciudad
        $ciudadDestino = $ciudades[$despacho->ofdestino] ?? $despacho->ofdestino;
    
        // Datos para el PDF
        $data = [
            'despacho' => $despacho,
            'sacas' => $sacas,
            'peso' => $despacho->peso,
            'totalPeso' => $totalPeso,
            'totalPaquetes' => $totalPaquetes,
            'ciudadOrigen' => $ciudadOrigen,
            'siglaOrigen' => $siglaOrigen,
            'ofdestino' => $despacho->ofdestino,
            'ciudadDestino' => $ciudadDestino,
            'categoria' => $despacho->categoria,
            'subclase' => $despacho->subclase,
            'ano' => $despacho->created_at->format('Y'),
            'nrodespacho' => $despacho->nrodespacho,
            'identificador' => $despacho->identificador,
            'created_at' => $despacho->created_at,
            'nropaquetesro' => $nropaquetesro,
            'nropaquetesbl' => $nropaquetesbl,
            'sacasm' => $sacasm,
            'listas' => $listas,
            'lcao' => $lcao,
            'totalContenidoR' => $totalContenidoR,
            'totalContenidoB' => $totalContenidoB,
            'totalContenido' => $totalContenido,
        ];
    
        // Crear el PDF usando la vista 'despacho.pdf.cn31'
        $pdf = PDF::loadView('despacho.pdf.cn', $data);
    
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'CN.pdf');
    }

    public function render()
    {
        $despachos = Despacho::where(function ($query) {
            $query->where('ofdestino', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('categoria', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('subclase', 'like', '%' . $this->searchTerm . '%');
        })
            ->whereIn('estado', ['APERTURA', 'CERRADO', 'REAPERTURA']) // Filtra solo los estados deseados
            ->paginate($this->perPage);

        return view('livewire.iniciar', [
            'despachos' => $despachos,
        ]);
    }
}
