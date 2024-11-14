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
            'etiqueta' => 'required|string|max:50',
            'peso' => 'nullable|numeric',
            'nropaquetes' => 'nullable|integer',
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

        // Crear la variable receptaculo
        $pesoFormateado = str_pad(str_replace(['.', ','], '', number_format($request->peso, 1, '', '')), 4, '0', STR_PAD_LEFT);
        $receptaculo = $identificadorSaca . $pesoFormateado;

        // Crear la nueva saca con el valor de nrosaca, identificador y receptaculo
        Saca::create([
            'despacho_id' => $request->despacho_id,
            'tipo' => $request->tipo,
            'nrosaca' => $nextNroSaca,
            'identificador' => $identificadorSaca,
            'etiqueta' => $request->etiqueta,
            'peso' => $request->peso,
            'nropaquetes' => $request->nropaquetes,
            'estado' => 'APERTURA',
            'receptaculo' => $receptaculo, // Guardar la variable receptaculo
        ]);

        return redirect()->back()->with('message', 'Saca creada exitosamente');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tipo' => 'required|string|max:50',
            'peso' => 'nullable|numeric',
            'etiqueta' => 'required|string|max:50',
            'nropaquetes' => 'nullable|integer',
        ]);

        // Buscar la saca a actualizar
        $saca = Saca::findOrFail($id);

        // Formatear el peso en formato 0000 (sin puntos ni comas)
        $pesoFormateado = str_pad(str_replace(['.', ','], '', number_format($request->peso, 1, '', '')), 4, '0', STR_PAD_LEFT);

        // Actualizar el valor de receptaculo con identificador + peso formateado
        $receptaculo = $saca->identificador . $pesoFormateado;

        // Actualizar la saca con los nuevos valores
        $saca->update([
            'tipo' => $request->tipo,
            'peso' => $request->peso,
            'nropaquetes' => $request->nropaquetes,
            'etiqueta' => $request->etiqueta,
            'receptaculo' => $receptaculo, // Guardar el nuevo valor de receptaculo
        ]);

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

        // Verificar si alguna saca tiene peso o nropaquetes en null o 0
        $incompleteSacas = $sacas->contains(function ($saca) {
            return $saca->peso === null || $saca->peso == 0 || $saca->nropaquetes === null || $saca->nropaquetes == 0;
        });

        // Si existe alguna saca incompleta, redirigir con un mensaje de error
        if ($incompleteSacas) {
            return redirect()->back()->with('error', 'No se puede cerrar el despacho. Todas las sacas deben tener valores válidos de peso y número de paquetes.');
        }

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
