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

        // Último dígito del año
        $ultimoDigitoAnio = substr(date('Y'), -1);

        // Despacho con ceros a la izquierda (formato 4 dígitos)
        $despachoFormateado = str_pad($this->despacho, 4, '0', STR_PAD_LEFT);

        // Construir receptáculo
        $this->receptaculo = strtoupper(
            $this->origen .
                $this->destino .
                $this->categoria .
                $this->subclase .
                $ultimoDigitoAnio .
                $despachoFormateado
        );

        Cn::updateOrCreate(
            ['id' => $this->cn35_id],
            [
                'despacho'          => $this->despacho,
                'origen'            => strtoupper($this->origen),
                'destino'           => strtoupper($this->destino),
                'saca'              => $this->saca,
                'categoria'         => strtoupper($this->categoria),
                'subclase'          => strtoupper($this->subclase),
                'servicio'          => strtoupper($this->servicio),
                'paquetes'          => $this->paquetes,
                'peso'              => $this->peso,
                'aduana'            => strtoupper($this->aduana),
                'codigo_manifiesto' => strtoupper($this->codigo_manifiesto),
                'receptaculo'       => $this->receptaculo,
                'identificador'     => strtoupper($this->identificador),
            ]
        );

        session()->flash('message', $this->cn35_id ? 'Registro actualizado.' : 'Registro creado.');
        $this->cerrarModal();
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
