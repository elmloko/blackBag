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
    public function store(Request $request)
    {
        $request->validate([
            'despacho_id' => 'required',
            'tipo' => 'required|string|max:50',
            'peso' => 'required|numeric',
            'nropaquetes' => 'required|integer',
        ]);

        Saca::create($request->all());

        return redirect()->back()->with('message', 'Saca creada exitosamente');
    }
}
