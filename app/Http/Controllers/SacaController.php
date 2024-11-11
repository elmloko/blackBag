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
        $sacas = Saca::where('despacho_id', $id)->with('contenido')->get();

        // Obtener el identificador del despacho
        $despacho = Despacho::findOrFail($id);
        $identificadorDespacho = $despacho->identificador;

        return view('sacas.crear', compact('id', 'sacas', 'identificadorDespacho'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'despacho_id' => 'required',
            'tipo' => 'required|string|max:50',
        ]);

        // Obtener el último valor de nrosaca para el despacho actual y calcular el siguiente
        $lastSaca = Saca::where('despacho_id', $request->despacho_id)
            ->orderBy('nrosaca', 'desc')
            ->first();
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
            'nrosaca' => $nextNroSaca,
            'identificador' => $identificadorSaca,
            'estado' => 'APERTURA',
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
    public function cerrar($id)
    {
        // Obtener todas las sacas relacionadas al despacho
        $sacas = Saca::where('despacho_id', $id)->get();

        // Calcular la suma total de peso y número de paquetes
        $totalPeso = $sacas->sum('peso');
        $totalPaquetes = $sacas->sum('nropaquetes');

        // Cambiar el estado de todas las sacas a 'CERRADO'
        Saca::where('despacho_id', $id)->update(['estado' => 'CERRADO']);

        // Cambiar el estado del despacho a 'CERRADO' y guardar los totales
        $despacho = Despacho::findOrFail($id);
        $despacho->update([
            'estado' => 'CERRADO',
            'peso' => $totalPeso,
            'nroenvase' => $totalPaquetes,
        ]);

        // Redirigir a la pantalla /iniciar con un mensaje de éxito
        return redirect('/iniciar')->with('message', 'Despacho cerrado exitosamente con todos los datos actualizados');
    }
}
