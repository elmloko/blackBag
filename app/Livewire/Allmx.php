<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Despacho;
use App\Models\Contenido;
use App\Models\Saca;
use App\Models\Eventos;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class Allmx extends Component
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
        'PERU/LIMA' => 'PELIM',
    ];

    public function render()
    {
        $despachos = Despacho::where(function ($query) {
            $query->where('ofdestino', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('categoria', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('subclase', 'like', '%' . $this->searchTerm . '%');
        })
            ->where('service', 'MX') // Filtrar solo los despachos con service = 'LC'
            ->where('depto', auth()->user()->city) // Filtrar por el departamento del usuario
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.allmx', [
            'despachos' => $despachos,
        ]);
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
            'PELIM' => 'PELIM - PERU/LIMA',
        ];

        // Obtener la ciudad del usuario como origen
        $ciudadOrigen = auth()->user()->city;
        $siglaOrigen = array_search($ciudadOrigen, $ciudades) ?: $ciudadOrigen;

        // Encontrar el despacho
        $despacho = Despacho::findOrFail($despachoId);

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
    public function reimprimirDespacho($despachoId)
    {
        $despacho = Despacho::findOrFail($despachoId);
        $sacas = Saca::where('despacho_id', $despacho->id)->get();
    
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
            'PELIM' => 'PELIM - PERU/LIMA',
        ];
    
        $ciudadOrigen = auth()->user()->city;
        $siglaOrigen = array_search($ciudadOrigen, $ciudades) ?: $ciudadOrigen;
        $ciudadDestino = $ciudades[$despacho->ofdestino] ?? $despacho->ofdestino;
    
        // Variables acumuladoras
        $totalPeso = $totalPaquetes = 0;
        $nropaquetesro = $nropaquetesbl = 0;
        $sacasm = $listas = $lcao = 0;
        $totalContenidoR = $totalContenidoB = 0;
    
        foreach ($sacas as $saca) {
            $contenido = Contenido::where('saca_id', $saca->id)->get();
    
            foreach ($contenido as $item) {
                if ($item->nropaquetesro > 0) $totalContenidoR += 1;
                if ($item->nropaquetesbl > 0) $totalContenidoB += 1;
    
                $nropaquetesro += $item->nropaquetesro;
                $nropaquetesbl += $item->nropaquetesbl;
                $sacasm += $item->sacasm;
                $listas += $item->listas;
                $lcao += $item->lcao;
    
                $totalPaquetes += $item->nropaquetesro + $item->nropaquetesbl;
            }
        }
    
        $totalContenido = $totalContenidoR + $totalContenidoB + $sacasm;
    
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
    
        $pdf = PDF::loadView('despacho.pdf.cn', $data);
    
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'CN_reimpresion.pdf');
    }
}
