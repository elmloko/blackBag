<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Despacho;
use App\Models\Saca;
use App\Models\Eventos;
use Carbon\Carbon;
use App\Exports\ExpedicionExport;
use Maatwebsite\Excel\Facades\Excel;

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
            ->paginate($this->perPage);

        return view('livewire.expedicion', [
            'despachos' => $despachos,
        ]);
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
}
