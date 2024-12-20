<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contenido;
use App\Models\Saca;
use App\Models\Eventos;

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
            'correotradicional' => 'nullable|integer',
            'encomiendas' => 'nullable|integer',
            'enviotrans' => 'nullable|integer',
            'nropaquetesro' => 'nullable|integer',
            'nropaquetesbl' => 'nullable|integer',
            'nropaquetesems' => 'nullable|integer',
            'nropaquetescp' => 'nullable|integer',
            'nropaquetesco' => 'nullable|integer',
            'nropaquetessn' => 'nullable|integer',
            'nropaquetessu' => 'nullable|integer',
            'nropaqueteset' => 'nullable|integer',
            'nropaquetesii' => 'nullable|integer',
            'nropaquetesof' => 'nullable|integer',
        ]);

        // Creación del nuevo contenido en la base de datos
        Contenido::create([
            'saca_id' => $request->saca_id,
            'descripcion' => $request->descripcion,
            'lcao' => $request->lcao,
            'sacasm' => $request->sacasm,
            'listas' => $request->listas,
            'correotradicional' => $request->correotradicional,
            'encomiendas' => $request->encomiendas,
            'enviotrans' => $request->enviotrans,
            'nropaquetesro' => $request->nropaquetesro,
            'nropaquetesbl' => $request->nropaquetesbl,
            'nropaquetesems' => $request->nropaquetesems,
            'nropaquetescp' => $request->nropaquetescp,
            'nropaquetesco' => $request->nropaquetesco,
            'nropaquetessn' => $request->nropaquetessn,
            'nropaquetessu' => $request->nropaquetessu,
            'nropaqueteset' => $request->nropaqueteset,
            'nropaquetesii' => $request->nropaquetesii,
            'nropaquetesof' => $request->nropaquetesof,
        ]);

        // Recuperar la saca relacionada
        $saca = Saca::find($request->saca_id);
        if ($saca) {
            // Calcular la suma de nropaquetesro y nropaquetesbl
            $totalPaquetes = ($request->nropaquetesro ?? 0) + ($request->nropaquetesbl ?? 0) + ($request->nropaquetesems ?? 0) + ($request->nropaquetescp ?? 0) + ($request->nropaquetesco ?? 0) + ($request->nropaquetessn ?? 0) + ($request->nropaquetessu ?? 0)  + ($request->nropaqueteset ?? 0) + ($request->nropaquetesii ?? 0) + ($request->nropaquetesof ?? 0);

            // Actualizar el campo nropaquetes en la saca
            $saca->nropaquetes = $totalPaquetes;
            $saca->save();

            // Registrar el evento en la tabla Eventos
            Eventos::create([
                'action' => 'DECLARACION DE CONTENIDO',
                'descripcion' => 'Contenido declarado en saca postal',
                'identificador' => $saca->receptaculo,
                'user_id' => auth()->user()->name,
            ]);
        }

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
            'correotradicional' => 'nullable|integer',
            'encomiendas' => 'nullable|integer',
            'enviotrans' => 'nullable|integer',
            'nropaquetesro' => 'nullable|integer',
            'nropaquetesbl' => 'nullable|integer',
            'nropaquetesems' => 'nullable|integer',
            'nropaquetescp' => 'nullable|integer',
            'nropaquetesco' => 'nullable|integer',
            'nropaquetessn' => 'nullable|integer',
            'nropaquetessu' => 'nullable|integer',
            'nropaqueteset' => 'nullable|integer',
            'nropaquetesii' => 'nullable|integer',
            'nropaquetesof' => 'nullable|integer',
        ]);

        // Encontrar el contenido existente y actualizar sus datos
        $contenido = Contenido::findOrFail($id);
        $contenido->update([
            'descripcion' => $request->descripcion,
            'lcao' => $request->lcao,
            'sacasm' => $request->sacasm,
            'listas' => $request->listas,
            'correotradicional' => $request->correotradicional,
            'encomiendas' => $request->encomiendas,
            'enviotrans' => $request->enviotrans,
            'nropaquetesro' => $request->nropaquetesro,
            'nropaquetesbl' => $request->nropaquetesbl,
            'nropaquetesems' => $request->nropaquetesems,
            'nropaquetescp' => $request->nropaquetescp,
            'nropaquetesco' => $request->nropaquetesco,
            'nropaquetessn' => $request->nropaquetessn,
            'nropaquetessu' => $request->nropaquetessu,
            'nropaqueteset' => $request->nropaqueteset,
            'nropaquetesii' => $request->nropaquetesii,
            'nropaquetesof' => $request->nropaquetesof,
        ]);

        // Calcular la suma de nropaquetesro y nropaquetesbl
        $totalPaquetes = ($request->nropaquetesro ?? 0) + ($request->nropaquetesbl ?? 0) + ($request->nropaquetesems ?? 0) + ($request->nropaquetescp ?? 0) + ($request->nropaquetesco ?? 0) + ($request->nropaquetessn ?? 0) + ($request->nropaquetessu ?? 0)  + ($request->nropaqueteset ?? 0) + ($request->nropaquetesii ?? 0) + ($request->nropaquetesof ?? 0);

        // Actualizar el campo nropaquetes en el registro de Saca
        $saca = Saca::find($contenido->saca_id);
        if ($saca) {
            $saca->nropaquetes = $totalPaquetes;
            $saca->save();

            // Registrar el evento en la tabla Eventos
            Eventos::create([
                'action' => 'ACTUALIZACION DE CONTENIDO',
                'descripcion' => 'Actualización de contenido en saca postal',
                'identificador' => $saca->receptaculo, // Usa el campo 'receptaculo' de la saca
                'user_id' => auth()->user()->name, // Guarda el ID del usuario autenticado
            ]);
        }

        // Redirección después de la actualización exitosa
        return redirect()->back()->with('message', 'Contenido actualizado exitosamente');
    }
}
