<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Despacho;
use App\Models\Saca;

class Dashboardadmin extends Component
{
    public function render()
    {
        $totalDespachos = Despacho::count();
        $totalSacas = Saca::count();
        $totalDespachosAbiertos = Despacho::where('estado', 'APERTURA')->count();
        $totalDespachosCerrados = Despacho::where('estado', 'CERRADO')->count();
        $totalDespachosExpeditos = Despacho::where('estado', 'EXPEDICION')->count();
        $totalDespachosAdmitidos = Despacho::where('estado', 'ADMITIDO')->count();

        return view('livewire.dashboardadmin', [
            'totalDespachos' => $totalDespachos,
            'totalSacas' => $totalSacas,
            'totalDespachosAbiertos' => $totalDespachosAbiertos,
            'totalDespachosCerrados' => $totalDespachosCerrados,
            'totalDespachosExpeditos' => $totalDespachosExpeditos,
            'totalDespachosAdmitidos' => $totalDespachosAdmitidos,
        ]);
    }
}
