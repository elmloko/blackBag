<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contenido;

class ContenidoController extends Controller
{
    public function store(Request $request)
    {
        // Validación de los datos recibidos
        $request->validate([
            'saca_id' => 'required|integer',
            'descripcion' => 'required|string|max:50',
            'pesom' => 'nullable|numeric',
            'pesol' => 'nullable|numeric',
            'pesou' => 'nullable|numeric',
            'nropaquetesm' => 'nullable|integer',
            'nropaquetesl' => 'nullable|integer',
            'nropaquetesu' => 'nullable|integer',
            'tipom' => 'nullable|string|max:50',
            'tipol' => 'nullable|string|max:50',
            'tipou' => 'nullable|string|max:50',
        ]);

        // Creación del nuevo contenido en la base de datos
        Contenido::create([
            'saca_id' => $request->saca_id,
            'descripcion' => $request->descripcion,
            'pesom' => $request->pesom,
            'pesol' => $request->pesol,
            'pesou' => $request->pesou,
            'nropaquetesm' => $request->nropaquetesm,
            'nropaquetesl' => $request->nropaquetesl,
            'nropaquetesu' => $request->nropaquetesu,
            'tipom' => $request->tipom,
            'tipol' => $request->tipol,
            'tipou' => $request->tipou,
        ]);

        // Redirección después de la creación exitosa
        return redirect()->back()->with('message', 'Contenido creado exitosamente');
    }
    public function update(Request $request, $id)
    {
        // Validación de los datos de entrada
        $request->validate([
            'descripcion' => 'required|string|max:50',
            'pesom' => 'nullable|numeric',
            'pesol' => 'nullable|numeric',
            'pesou' => 'nullable|numeric',
            'nropaquetesm' => 'nullable|integer',
            'nropaquetesl' => 'nullable|integer',
            'nropaquetesu' => 'nullable|integer',
            'tipom' => 'nullable|string|max:50',
            'tipol' => 'nullable|string|max:50',
            'tipou' => 'nullable|string|max:50',
        ]);

        // Encontrar el contenido existente y actualizar sus datos
        $contenido = Contenido::findOrFail($id);
        $contenido->update([
            'descripcion' => $request->descripcion,
            'pesom' => $request->pesom,
            'pesol' => $request->pesol,
            'pesou' => $request->pesou,
            'nropaquetesm' => $request->nropaquetesm,
            'nropaquetesl' => $request->nropaquetesl,
            'nropaquetesu' => $request->nropaquetesu,
            'tipom' => $request->tipom,
            'tipol' => $request->tipol,
            'tipou' => $request->tipou,
        ]);

        // Redirección después de la actualización exitosa
        return redirect()->back()->with('message', 'Contenido actualizado exitosamente');
    }
}
