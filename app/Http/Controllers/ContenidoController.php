<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contenido;
use App\Models\Saca;

class ContenidoController extends Controller
{
    public function store(Request $request)
    {
        // Validación de los datos recibidos
        $request->validate([
            'saca_id' => 'required|integer',
            'descripcion' => 'required|string|max:50',
            'lcao' => 'nullable|integer',
            'sacasm' => 'nullable|integer',
            'listas' => 'nullable|integer',
        ]);
    
        // Creación del nuevo contenido en la base de datos
        Contenido::create([
            'saca_id' => $request->saca_id,
            'descripcion' => $request->descripcion,
            'lcao' => $request->lcao,
            'sacasm' => $request->sacasm,
            'listas' => $request->listas,
        ]);
    
        // Redirección después de la creación exitosa
        return redirect()->back()->with('message', 'Contenido creado exitosamente');
    }
    

    public function update(Request $request, $id)
    {
        // Validación de los datos de entrada
        $request->validate([
            'descripcion' => 'required|string|max:50',
            'lcao' => 'nullable|integer',
            'sacasm' => 'nullable|integer',
            'listas' => 'nullable|integer',
        ]);
    
        // Encontrar el contenido existente y actualizar sus datos
        $contenido = Contenido::findOrFail($id);
        $contenido->update([
            'descripcion' => $request->descripcion,
            'lcao' => $request->lcao,
            'sacasm' => $request->sacasm,
            'listas' => $request->listas,
        ]);
    
        // Redirección después de la actualización exitosa
        return redirect()->back()->with('message', 'Contenido actualizado exitosamente');
    }
    
}
