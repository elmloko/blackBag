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

        // Verifica que la oficina seleccionada esté definida
        if (!$this->ofdestino) {
            session()->flash('message', 'Por favor, selecciona una oficina.');
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

        // Construye el identificador concatenando los valores deseados
        $identificador = $this->ofdestino . $this->categoria . $this->subclase . $ultimoDigitoAno . $this->nrodespacho;

        // Guarda el despacho en la base de datos con el estado "ABIERTO", el último dígito del año, y el identificador
        Despacho::create([
            'categoria' => $this->categoria,
            'ofdestino' => $this->ofdestino,
            'subclase' => $this->subclase,
            'nrodespacho' => $this->nrodespacho,
            'fecha_hora_creacion' => $this->fechaHoraActual,
            'estado' => 'ABIERTO',
            'ano' => $ultimoDigitoAno,
            'identificador' => $identificador,  // Guarda el identificador generado
        ]);

        session()->flash('message', 'Despacho creado exitosamente.');

        // Cierra el modal de confirmación y restablece los campos
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
