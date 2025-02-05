<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Despacho;
use App\Models\Saca;
use App\Models\Contenido; // si tienes un modelo para contenido

class ApiController extends Controller
{
    public function apertura()
    {
        $despachos = Despacho::with(['sacas.contenidos'])
            ->where('estado', 'APERTURA')
            ->orwhere('estado', 'REAPERTURA')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $despachos
        ], 200);
    }
    public function cerrado()
    {
        $despachos = Despacho::with(['sacas.contenidos'])
            ->where('estado', 'CERRADO')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $despachos
        ], 200);
    }
    public function expedicion()
    {
        $despachos = Despacho::with(['sacas.contenidos'])
            ->where('estado', 'EXPEDICION')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $despachos
        ], 200);
    }
    public function observado()
    {
        $despachos = Despacho::with(['sacas.contenidos'])
            ->where('estado', 'OBSERVADO')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $despachos
        ], 200);
    }
    public function admitido()
    {
        $despachos = Despacho::with(['sacas.contenidos'])
            ->where('estado', 'ADMITIDO')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $despachos
        ], 200);
    }
}
