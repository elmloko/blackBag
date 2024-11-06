<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Saca;
use App\Models\Despacho;

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

        // Obtener el último valor de nrosaca y calcular el siguiente
        $lastSaca = Saca::orderBy('id', 'desc')->first();
        $nextNroSaca = $lastSaca ? sprintf('%03d', intval($lastSaca->nrosaca) + 1) : '001';

        // Obtener el identificador del despacho
        $despacho = Despacho::find($request->despacho_id);
        $identificadorDespacho = $despacho ? $despacho->identificador : '';

        // Concatenar el identificador del despacho con el número de saca
        $identificadorSaca = $identificadorDespacho . $nextNroSaca;

        // Crear la nueva saca con el valor de nrosaca y el identificador
        Saca::create([
            'despacho_id' => $request->despacho_id,
            'tipo' => $request->tipo,
            'peso' => $request->peso,
            'nropaquetes' => $request->nropaquetes,
            'nrosaca' => $nextNroSaca,
            'identificador' => $identificadorSaca,
        ]);

        return redirect()->back()->with('message', 'Saca creada exitosamente');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'tipo' => 'required|string|max:50',
            'peso' => 'required|numeric',
            'nropaquetes' => 'required|integer',
        ]);

        $saca = Saca::findOrFail($id);
        $saca->update($request->only('tipo', 'peso', 'nropaquetes'));

        return redirect()->back()->with('message', 'Saca actualizada exitosamente');
    }

    public function destroy($id)
    {
        $saca = Saca::findOrFail($id);
        $saca->delete();

        return redirect()->back()->with('message', 'Saca eliminada exitosamente');
    }
}
