<?php

namespace App\Livewire;

use App\Models\Cn;
use Livewire\Component;
use Livewire\WithPagination;

class Cn35 extends Component
{
    use WithPagination;

    public $search = '';
    public $searchInput = '';
    public $modal = false;
    public $modalExtra = false;
    public $detalles = [];


    public $cn35_id;
    public $despacho, $origen, $destino, $saca, $categoria, $subclase, $servicio,
        $paquetes, $peso, $aduana, $codigo_manifiesto, $receptaculo, $identificador;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'despacho'          => 'nullable|integer',
        'origen'            => 'nullable|string|max:50',
        'destino'           => 'nullable|string|max:50',
        'saca'              => 'nullable|integer',
        'categoria'         => 'nullable|string|max:50',
        'subclase'          => 'nullable|string|max:50',
        'servicio'          => 'nullable|string|max:50',
        'paquetes'          => 'nullable|integer',
        'peso'              => 'nullable|numeric',
        'aduana'            => 'nullable|string|max:50',
        'codigo_manifiesto' => 'nullable|string|max:50',
        'receptaculo'       => 'nullable|string|max:50',
        'identificador'     => 'nullable|string|max:50',
    ];

    public function mount()
    {
        $this->searchInput = $this->search;
    }

    public function buscar()
    {
        $this->search = $this->searchInput;
        $this->resetPage();
    }

    public function abrirModalExtra()
    {
        $this->modalExtra = true;
        $this->detalles = [];

        for ($i = 0; $i < $this->saca; $i++) {
            $this->detalles[] = [
                'paquetes' => null,
                'peso' => null,
                'aduana' => 'SI',
                'codigo_manifiesto' => null,
            ];
        }
    }

    public function cerrarModalExtra()
    {
        $this->modalExtra = false;
    }

    public function abrirModal()
    {
        $this->resetExcept('search', 'searchInput');
        $this->modal = true;
    }

    public function cerrarModal()
    {
        $this->modal = false;
    }

    public function guardar()
    {
        $this->validate();

        foreach ($this->detalles as $detalle) {
            $ultimoDigitoAnio = substr(date('Y'), -1);
            $despachoFormateado = str_pad($this->despacho, 4, '0', STR_PAD_LEFT);
            $receptaculo = strtoupper(
                $this->origen . $this->destino . $this->categoria . $this->subclase . $ultimoDigitoAnio . $despachoFormateado
            );

            Cn::create([
                'despacho'          => $this->despacho,
                'origen'            => strtoupper($this->origen),
                'destino'           => strtoupper($this->destino),
                'saca'              => $this->saca,
                'categoria'         => strtoupper($this->categoria),
                'subclase'          => strtoupper($this->subclase),
                'servicio'          => strtoupper($this->servicio),
                'paquetes'          => $detalle['paquetes'],
                'peso'              => $detalle['peso'],
                'aduana'            => strtoupper($detalle['aduana']),
                'codigo_manifiesto' => strtoupper($detalle['codigo_manifiesto']),
                'receptaculo'       => $receptaculo,
                'identificador'     => strtoupper($this->identificador),
            ]);
        }

        session()->flash('message', 'Registros creados.');
        $this->cerrarModal();
        $this->cerrarModalExtra();
    }


    public function editar($id)
    {
        $registro = Cn::findOrFail($id);
        $this->fill($registro->toArray());
        $this->cn35_id = $registro->id;
        $this->modal = true;
    }

    public function eliminar($id)
    {
        Cn::findOrFail($id)->delete();
        session()->flash('message', 'Registro eliminado.');
    }

    public function render()
    {
        $registros = Cn::where('origen', 'like', '%' . $this->search . '%')
            ->orWhere('destino', 'like', '%' . $this->search . '%')
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.cn35', [
            'registros' => $registros
        ]);
    }
}
