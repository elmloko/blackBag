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
        // Array de traducción de oficinas a ciudades
        $oficinas = [
            'BOLPZ' => 'LA PAZ',
            'BOTJA' => 'TARIJA',
            'BOPOI' => 'POTOSI',
            'BOCIJ' => 'PANDO',
            'BOORU' => 'ORURO',
            'BOTDD' => 'BENI',
            'BOSRE' => 'SUCRE',
            'BOSRZ' => 'SANTA CRUZ',
        ];

        // Buscar el registro en la tabla Saca
        $registro = Saca::where('receptaculo', $this->searchReceptaculo)->first();

        if ($registro) {
            // Verificar si el estado de Saca es "CERRADO"
            if ($registro->estado !== 'CERRADO') {
                session()->flash('error', 'El estado del receptáculo debe ser "CERRADO" para su recepción.');
                return;
            }

            // Obtener el despacho relacionado a través de la relación en el modelo Saca
            $despacho = $registro->despacho; // Asegúrate de tener la relación en el modelo Saca

            // Verificar si el destino de la oficina coincide con la ciudad del usuario
            if ($despacho && isset($oficinas[$despacho->ofdestino]) && auth()->user()->city == $oficinas[$despacho->ofdestino]) {
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
            // Busca el registro en la tabla Saca
            $saca = Saca::find($registro['id']);

            if ($saca) {
                // Cambia el estado de Saca a ADMITIDO
                $saca->estado = 'ADMITIDO';
                $saca->save();

                // Busca el registro relacionado en la tabla Despacho usando despacho_id
                $despacho = Despacho::find($saca->despacho_id);

                if ($despacho) {
                    // Cambia el estado de Despacho a ADMITIDO
                    $despacho->estado = 'ADMITIDO';
                    $despacho->save();
                }
            }
        }

        // Limpia los registros seleccionados
        $this->registrosSeleccionados = [];

        // Muestra un mensaje de éxito al usuario
        session()->flash('message', 'Todos los registros seleccionados fueron admitidos exitosamente.');

        // Redirige a la página anterior
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        $despachos = Despacho::where(function ($query) {
            $query->where('ofdestino', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('categoria', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('subclase', 'like', '%' . $this->searchTerm . '%');
        })
        ->paginate($this->perPage);
    
        // Agregar el conteo de sacas admitidas y cerradas para cada despacho
        foreach ($despachos as $despacho) {
            $despacho->sacas_admitidas = Saca::where('despacho_id', $despacho->id)->where('estado', 'ADMITIDO')->count();
            $despacho->sacas_cerradas = Saca::where('despacho_id', $despacho->id)->where('estado', 'CERRADO')->count();
        }
    
        return view('livewire.admitir', [
            'despachos' => $despachos,
        ]);
    }    
}
