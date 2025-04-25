<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Despacho;
use App\Models\Saca;
use App\Models\Contenido;
use App\Models\Eventos;
use Carbon\Carbon;
use App\Exports\ExpedicionExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class Expedicion extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $categoria;
    public $ofdestino;
    public $subclase;
    public $nrodespacho;
    public $fechaInicio;
    public $fechaFin;
    public $perPage = 10;

    public function exportToExcel()
    {
        $this->validate([
            'fechaInicio' => 'required|date',
            'fechaFin' => 'required|date|after_or_equal:fechaInicio',
        ]);

        return Excel::download(new ExpedicionExport($this->fechaInicio, $this->fechaFin), 'expedicion_report.xlsx');
    }

    public function render()
    {
        $despachos = Despacho::where(function ($query) {
            $query->where('ofdestino', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('categoria', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('subclase', 'like', '%' . $this->searchTerm . '%');
        })
            ->whereIn('estado', ['EXPEDICION','OBSERVADO'])
            ->where('service', 'LC')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.expedicion', [
            'despachos' => $despachos,
        ]);
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

    public function reaperturarDespacho($despachoId)
    {
        // Cambiar el estado de todas las sacas relacionadas a 'APERTURA'
        Saca::where('despacho_id', $despachoId)->update(['estado' => 'APERTURA']);

        // Cambiar el estado del despacho a 'REAPERTURA'
        $despacho = Despacho::findOrFail($despachoId);
        $despacho->update(['estado' => 'OBSERVADO']);

        // Obtener la primera saca asociada al despacho para el identificador
        $saca = Saca::where('despacho_id', $despachoId)->first();
        $identificador = $saca ? $saca->receptaculo : 'SIN SACAS ASOCIADAS';

        // Registrar el evento en la tabla Eventos
        Eventos::create([
            'action' => 'INTERVENIR',
            'descripcion' => 'Saca intervenida',
            'identificador' => $identificador,
            'user_id' => auth()->user()->name, // Usa el ID del usuario autenticado
        ]);

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
}
