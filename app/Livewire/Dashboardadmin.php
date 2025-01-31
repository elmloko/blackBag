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
        // Estadísticas que ya tienes
        $totalDespachos = Despacho::count();
        $totalSacas = Saca::count();
        $totalDespachosAbiertos = Despacho::where('estado', 'APERTURA')->count();
        $totalDespachosCerrados = Despacho::where('estado', 'CERRADO')->count();
        $totalDespachosExpeditos = Despacho::where('estado', 'EXPEDICION')->count();
        $totalDespachosAdmitidos = Despacho::where('estado', 'ADMITIDO')->count();

        // 1) Cantidad de DESPACHOS por departamento
        // -----------------------------------------
        $despachosPorDepartamento = Despacho::select(
                'depto',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('depto')
            ->get();

        // 2) Cantidad de SACAS por departamento
        //    Se asume que saca.despacho_id = despacho.id (aunque sea varchar).
        //    Unimos despacho con saca, agrupando por departamento.
        // -----------------------------------------
        $sacasPorDepartamento = DB::table('despacho')
            ->join('saca', 'saca.despacho_id', '=', 'despacho.id')
            ->select('despacho.depto', DB::raw('COUNT(saca.id) as total'))
            ->groupBy('despacho.depto')
            ->get();

        // 3) Cantidad de PAQUETES por departamento
        //    Unimos despacho->saca->contenido y contamos cuántos registros
        //    hay en contenido para cada departamento.
        //    (Se asume un "paquete" por cada fila en 'contenido'. Si la
        //    lógica difiere, ajusta la consulta.)
        // -----------------------------------------
        $paquetesPorDepartamento = DB::table('despacho')
            ->join('saca', 'saca.despacho_id', '=', 'despacho.id')
            ->join('contenido', 'contenido.saca_id', '=', 'saca.id')
            ->select('despacho.depto', DB::raw('COUNT(contenido.id) as total'))
            ->groupBy('despacho.depto')
            ->get();

        // Retornamos todo a la vista Livewire
        return view('livewire.dashboardadmin', [
            'totalDespachos'             => $totalDespachos,
            'totalSacas'                 => $totalSacas,
            'totalDespachosAbiertos'     => $totalDespachosAbiertos,
            'totalDespachosCerrados'     => $totalDespachosCerrados,
            'totalDespachosExpeditos'    => $totalDespachosExpeditos,
            'totalDespachosAdmitidos'    => $totalDespachosAdmitidos,

            'despachosPorDepartamento'   => $despachosPorDepartamento,
            'sacasPorDepartamento'       => $sacasPorDepartamento,
            'paquetesPorDepartamento'    => $paquetesPorDepartamento,
        ]);
    }
}
