<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Despacho;
use App\Models\Saca;
use App\Models\Contenido; // si tienes un modelo para contenido
use Illuminate\Support\Facades\DB;

class Dashboardadmin extends Component
{
    public function render()
    {
        // -- ESTADÍSTICAS GLOBALES (SIN FILTRO) --
        $totalDespachos = Despacho::count();
        $totalSacas = Saca::count();
        $totalDespachosAbiertos = Despacho::where('estado', 'APERTURA')->count();
        $totalDespachosCerrados = Despacho::where('estado', 'CERRADO')->count();
        $totalDespachosExpeditos = Despacho::where('estado', 'EXPEDICION')->count();
        $totalDespachosAdmitidos = Despacho::where('estado', 'ADMITIDO')->count();

        $despachosPorDepartamento = Despacho::select(
            'depto',
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('depto')
        ->get();

        $sacasPorDepartamento = DB::table('despacho')
            ->join('saca', 'saca.despacho_id', '=', 'despacho.id')
            ->select('despacho.depto', DB::raw('COUNT(saca.id) as total'))
            ->groupBy('despacho.depto')
            ->get();

        $paquetesPorDepartamento = DB::table('despacho')
            ->join('saca', 'saca.despacho_id', '=', 'despacho.id')
            ->join('contenido', 'contenido.saca_id', '=', 'saca.id')
            ->select('despacho.depto', DB::raw('COUNT(contenido.id) as total'))
            ->groupBy('despacho.depto')
            ->get();


        // -- ESTADÍSTICAS PARA EMS --
        $emsDespachos = Despacho::where('service', 'EMS'); // Filtramos EMS
        $totalDespachosEMS = $emsDespachos->count();

        // Sacas que pertenecen a despachos EMS
        $totalSacasEMS = Saca::whereHas('despacho', function ($query) {
            $query->where('service', 'EMS');
        })->count();

        // Otros estados para EMS
        $totalDespachosAbiertosEMS   = $emsDespachos->where('estado', 'APERTURA')->count();
        $totalDespachosCerradosEMS   = $emsDespachos->where('estado', 'CERRADO')->count();
        $totalDespachosExpeditosEMS  = $emsDespachos->where('estado', 'EXPEDICION')->count();
        $totalDespachosAdmitidosEMS  = $emsDespachos->where('estado', 'ADMITIDO')->count();

        // Despachos EMS por departamento
        $emsDespachosPorDepartamento = $emsDespachos
            ->select('depto', DB::raw('COUNT(*) as total'))
            ->groupBy('depto')
            ->get();

        // Sacas EMS por departamento
        $emsSacasPorDepartamento = DB::table('despacho')
            ->join('saca', 'saca.despacho_id', '=', 'despacho.id')
            ->where('despacho.service', 'EMS')
            ->select('despacho.depto', DB::raw('COUNT(saca.id) as total'))
            ->groupBy('despacho.depto')
            ->get();

        // Paquetes EMS por departamento
        $emsPaquetesPorDepartamento = DB::table('despacho')
            ->join('saca', 'saca.despacho_id', '=', 'despacho.id')
            ->join('contenido', 'contenido.saca_id', '=', 'saca.id')
            ->where('despacho.service', 'EMS')
            ->select('despacho.depto', DB::raw('COUNT(contenido.id) as total'))
            ->groupBy('despacho.depto')
            ->get();


        // -- ESTADÍSTICAS PARA LC --
        $lcDespachos = Despacho::where('service', 'LC'); // Filtramos LC
        $totalDespachosLC = $lcDespachos->count();

        // Sacas que pertenecen a despachos LC
        $totalSacasLC = Saca::whereHas('despacho', function ($query) {
            $query->where('service', 'LC');
        })->count();

        // Otros estados para LC
        $totalDespachosAbiertosLC   = $lcDespachos->where('estado', 'APERTURA')->count();
        $totalDespachosCerradosLC   = $lcDespachos->where('estado', 'CERRADO')->count();
        $totalDespachosExpeditosLC  = $lcDespachos->where('estado', 'EXPEDICION')->count();
        $totalDespachosAdmitidosLC  = $lcDespachos->where('estado', 'ADMITIDO')->count();

        // Despachos LC por departamento
        $lcDespachosPorDepartamento = $lcDespachos
            ->select('depto', DB::raw('COUNT(*) as total'))
            ->groupBy('depto')
            ->get();

        // Sacas LC por departamento
        $lcSacasPorDepartamento = DB::table('despacho')
            ->join('saca', 'saca.despacho_id', '=', 'despacho.id')
            ->where('despacho.service', 'LC')
            ->select('despacho.depto', DB::raw('COUNT(saca.id) as total'))
            ->groupBy('despacho.depto')
            ->get();

        // Paquetes LC por departamento
        $lcPaquetesPorDepartamento = DB::table('despacho')
            ->join('saca', 'saca.despacho_id', '=', 'despacho.id')
            ->join('contenido', 'contenido.saca_id', '=', 'saca.id')
            ->where('despacho.service', 'LC')
            ->select('despacho.depto', DB::raw('COUNT(contenido.id) as total'))
            ->groupBy('despacho.depto')
            ->get();


        return view('livewire.dashboardadmin', [
            // Globales
            'totalDespachos'          => $totalDespachos,
            'totalSacas'              => $totalSacas,
            'totalDespachosAbiertos'  => $totalDespachosAbiertos,
            'totalDespachosCerrados'  => $totalDespachosCerrados,
            'totalDespachosExpeditos' => $totalDespachosExpeditos,
            'totalDespachosAdmitidos' => $totalDespachosAdmitidos,

            'despachosPorDepartamento'=> $despachosPorDepartamento,
            'sacasPorDepartamento'    => $sacasPorDepartamento,
            'paquetesPorDepartamento' => $paquetesPorDepartamento,

            // EMS
            'totalDespachosEMS'            => $totalDespachosEMS,
            'totalSacasEMS'                => $totalSacasEMS,
            'totalDespachosAbiertosEMS'    => $totalDespachosAbiertosEMS,
            'totalDespachosCerradosEMS'    => $totalDespachosCerradosEMS,
            'totalDespachosExpeditosEMS'   => $totalDespachosExpeditosEMS,
            'totalDespachosAdmitidosEMS'   => $totalDespachosAdmitidosEMS,

            'emsDespachosPorDepartamento'  => $emsDespachosPorDepartamento,
            'emsSacasPorDepartamento'      => $emsSacasPorDepartamento,
            'emsPaquetesPorDepartamento'   => $emsPaquetesPorDepartamento,

            // LC
            'totalDespachosLC'            => $totalDespachosLC,
            'totalSacasLC'                => $totalSacasLC,
            'totalDespachosAbiertosLC'    => $totalDespachosAbiertosLC,
            'totalDespachosCerradosLC'    => $totalDespachosCerradosLC,
            'totalDespachosExpeditosLC'   => $totalDespachosExpeditosLC,
            'totalDespachosAdmitidosLC'   => $totalDespachosAdmitidosLC,

            'lcDespachosPorDepartamento'  => $lcDespachosPorDepartamento,
            'lcSacasPorDepartamento'      => $lcSacasPorDepartamento,
            'lcPaquetesPorDepartamento'   => $lcPaquetesPorDepartamento,
        ]);
    }
}
