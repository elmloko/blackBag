<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Despacho;
use Carbon\Carbon;
use App\Exports\ExpedicionExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Saca;

class Admitir extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $categoria;
    public $ofdestino;
    public $subclase;
    public $nrodespacho;
    public $fechaInicio;
    public $fechaFin;
    public $searchReceptaculo;
    public $registroSaca;
    public $registrosSeleccionados = [];
    public $perPage = 10;

    public function exportToExcel()
    {
        $this->validate([
            'fechaInicio' => 'required|date',
            'fechaFin' => 'required|date|after_or_equal:fechaInicio',
        ]);

        return Excel::download(new ExpedicionExport($this->fechaInicio, $this->fechaFin), 'expedicion_report.xlsx');
    }
    public function showModal()
    {
        $this->reset(['searchReceptaculo', 'registroSaca']);
        $this->dispatch('show-modal');
    }

    public function buscarReceptaculo()
    {
        // Buscar el registro en la tabla Saca
        $registro = Saca::where('receptaculo', $this->searchReceptaculo)->first();

        if ($registro) {
            // Obtener el despacho relacionado (aquí asumo que tienes alguna relación o que puedes obtenerlo de alguna forma)
            $despacho = Despacho::where('identificador', $registro->identificador)->first(); // Ajusta según cómo obtienes el despacho

            // Verificar si el destino de la oficina coincide con la ciudad del usuario
            if ($despacho && auth()->user()->city == $despacho->ofdestino) {
                // Si no existe en los seleccionados, agregarlo
                $existe = collect($this->registrosSeleccionados)->contains('id', $registro->id);
                if (!$existe) {
                    $this->registrosSeleccionados[] = $registro;
                }
            } else {
                // Si la ciudad del usuario no coincide con el destino de la oficina, rechazarlo
                session()->flash('error', 'El destino de la oficina no coincide con su ciudad.');
            }
        } else {
            // Si no se encuentra el registro, mostrar un mensaje de error
            session()->flash('error', 'No se encontró ningún registro con el receptáculo proporcionado.');
        }
    }

    public function quitarRegistro($id)
    {
        $this->registrosSeleccionados = collect($this->registrosSeleccionados)
            ->reject(fn($registro) => $registro['id'] == $id)
            ->toArray();
    }

    public function admitir()
    {
        foreach ($this->registrosSeleccionados as $registro) {
            $saca = Saca::find($registro['id']);
            if ($saca) {
                $saca->estado = 'ADMITIDO';
                $saca->save();
            }
        }

        $this->registrosSeleccionados = [];
        session()->flash('message', 'Todos los registros seleccionados fueron admitidos exitosamente.');
        $this->dispatch('hide-modal');
    }

    public function render()
    {
        $despachos = Despacho::where(function ($query) {
            $query->where('ofdestino', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('categoria', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('subclase', 'like', '%' . $this->searchTerm . '%');
        })
            ->whereIn('estado', ['EXPEDICION'])
            ->paginate($this->perPage);

        return view('livewire.admitir', [
            'despachos' => $despachos,
        ]);
    }
}
