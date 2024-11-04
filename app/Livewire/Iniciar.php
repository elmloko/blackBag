<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Despacho;
use Carbon\Carbon;

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

    public function crearDespacho()
    {
        $this->validate();

        // Genera el número de despacho y la fecha y hora actual
        $this->nrodespacho = rand(1000, 9999); // Número de despacho aleatorio, personalízalo si tienes otra lógica
        $this->fechaHoraActual = Carbon::now()->format('Y-m-d H:i:s');

        // Abre el modal de confirmación
        $this->dispatch('openConfirmModal');
    }

    public function confirmarGuardarDespacho()
    {
        // Guarda el despacho en la base de datos
        Despacho::create([
            'categoria' => $this->categoria,
            'ofdestino' => $this->ofdestino,
            'subclase' => $this->subclase,
            'nrodespacho' => $this->nrodespacho,
            'fecha_hora_creacion' => $this->fechaHoraActual,
        ]);

        session()->flash('message', 'Despacho creado exitosamente.');

        // Cierra el modal y restablece los campos
        $this->dispatch('closeConfirmModal');
        $this->reset(['categoria', 'ofdestino', 'subclase', 'nrodespacho', 'fechaHoraActual']);
    }

    public function render()
    {
        $despachos = Despacho::where('ofdestino', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('categoria', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('subclase', 'like', '%' . $this->searchTerm . '%')
            ->paginate($this->perPage);

        return view('livewire.iniciar', [
            'despachos' => $despachos,
        ]);
    }
}