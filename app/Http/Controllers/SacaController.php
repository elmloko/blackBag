<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Saca;

class SacaController extends Controller
{
    public function crear($id)
    {
        // Obtener las sacas relacionadas con el despacho
        $sacas = Saca::where('despacho_id', $id)->get();

        return view('sacas.crear', compact('id', 'sacas'));
    }
}
