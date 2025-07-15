<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Saca;
use App\Models\Eventos;
use App\Models\Despacho;

class SacaController extends Controller
{
    public function crear($id)
    {
        // Obtener las sacas relacionadas con el despacho
        $sacas = Saca::where('despacho_id', $id)->with('contenidos')->get();

        // Obtener el identificador del despacho
        $despacho = Despacho::findOrFail($id);
        $identificadorDespacho = $despacho->identificador;

        // Asumamos que el tipo de servicio está almacenado en el despacho
        $service = $despacho->service; // Ajusta 'service' según el campo de tu modelo

        return view('sacas.crear', compact('id', 'sacas', 'identificadorDespacho', 'service'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'despacho_id' => 'required',
            'tipo' => 'required|string|max:50',
            'etiqueta' => 'required|string|max:50',
            'peso' => 'nullable|numeric',
            'nropaquetes' => 'nullable|integer',
            'aduana' => 'required|string|max:2',
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
            'aduana' => $request->aduana,
        ]);

        Eventos::create([
            'action' => 'APERTURA',
            'descripcion' => 'Creacion de saca',
            'identificador' => $identificadorSaca,
            'user_id' => auth()->user()->name,
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
            'aduana' => 'required|string|max:2',
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
            'aduana' => $request->aduana,
        ]);

        Eventos::create([
            'action' => 'ACTUALIZACION',
            'descripcion' => 'Edicion de Saca',
            'identificador' => $receptaculo,
            'user_id' => auth()->user()->name,
        ]);

        return redirect()->back()->with('message', 'Saca actualizada exitosamente');
    }

    public function destroy($id)
    {
        // Encuentra la saca que se va a eliminar
        $saca = Saca::findOrFail($id);

        // Rescata el valor del campo 'receptaculo' antes de eliminarla
        $receptaculo = $saca->receptaculo;

        // Elimina la saca
        $saca->delete();

        // Crea un registro en la tabla Eventos
        Eventos::create([
            'action' => 'ELIMINACION', // Cambié a ELIMINACION para reflejar la acción
            'descripcion' => 'Eliminación de Saca',
            'identificador' => $receptaculo, // Usa el campo rescatado
            'user_id' => auth()->user()->name, // Guarda el ID del usuario autenticado
        ]);

        // Redirecciona con un mensaje de éxito
        return redirect()->back()->with('message', 'Saca eliminada exitosamente');
    }


    public function cerrar($id)
    {
        // Obtener todas las sacas relacionadas al despacho
        $sacas = Saca::where('despacho_id', $id)->get();
    
        // Verificar si alguna saca tiene peso o nropaquetes en null o 0
        $incompleteSacas = $sacas->contains(function ($saca) {
            return $saca->peso === null || $saca->peso == 0 || $saca->nropaquetes === null;
        });
    
        // Si existe alguna saca incompleta, redirigir con un mensaje de error
        if ($incompleteSacas) {
            return redirect()->back()->with('error', 'No se puede cerrar el despacho. Todas las sacas deben tener valores válidos de peso y número de paquetes.');
        }
    
        // Obtener el despacho y verificar su estado
        $despacho = Despacho::findOrFail($id);
        $estadoDespacho = $despacho->estado;
        $service = $despacho->service; // Asegúrate de que el campo 'service' existe en el modelo
    
        // Calcular la suma total de peso y número de paquetes
        $totalPeso = $sacas->sum('peso');
        $totalPaquetes = $sacas->sum('nropaquetes');
    
        // Definir el nuevo estado del despacho
        $nuevoEstadoDespacho = '';
        if (in_array($estadoDespacho, ['APERTURA', 'CERRADO',  'REAPERTURA'])) {
            $nuevoEstadoDespacho = 'CERRADO';
        } elseif (in_array($estadoDespacho, ['OBSERVADO', 'EXPEDICION'])) {
            $nuevoEstadoDespacho = 'EXPEDICION';
        }
    
        // Cambiar el estado de todas las sacas a 'CERRADO'
        Saca::where('despacho_id', $id)->update(['estado' => 'CERRADO']);
    
        // Cambiar el estado del despacho y guardar los totales
        $despacho->update([
            'estado' => $nuevoEstadoDespacho,
            'peso' => $totalPeso,
            'nroenvase' => $totalPaquetes,
        ]);
    
        // Registrar cada saca en la tabla Eventos
        foreach ($sacas as $saca) {
            Eventos::create([
                'action' => 'CERRADO',
                'descripcion' => 'Cierre de Saca',
                'identificador' => $saca->receptaculo, // Campo receptaculo para identificar la saca
                'user_id' => auth()->user()->name, // ID del usuario autenticado
            ]);
        }
    
        // Redirigir según el tipo de servicio y estado
        if ($service === 'EMS') {
            if ($nuevoEstadoDespacho === 'EXPEDICION') {
                return redirect('/expedicionems')->with('message', "Despacho EMS cerrado exitosamente con el estado: $nuevoEstadoDespacho y todos los datos actualizados.");
            } elseif ($nuevoEstadoDespacho === 'CERRADO') {
                return redirect('/iniciarems')->with('message', "Despacho EMS cerrado exitosamente con el estado: $nuevoEstadoDespacho y todos los datos actualizados.");
            }
        } elseif ($service === 'LC') {
            if ($nuevoEstadoDespacho === 'EXPEDICION') {
                return redirect('/expedicion')->with('message', "Despacho LC cerrado exitosamente con el estado: $nuevoEstadoDespacho y todos los datos actualizados.");
            } elseif ($nuevoEstadoDespacho === 'CERRADO') {
                return redirect('/iniciar')->with('message', "Despacho LC cerrado exitosamente con el estado: $nuevoEstadoDespacho y todos los datos actualizados.");
            }
        } elseif ($service === 'MX') {
            if ($nuevoEstadoDespacho === 'EXPEDICION') {
                return redirect('/expedicionmx')->with('message', "Despacho MX cerrado exitosamente con el estado: $nuevoEstadoDespacho y todos los datos actualizados.");
            } elseif ($nuevoEstadoDespacho === 'CERRADO') {
                return redirect('/iniciarmx')->with('message', "Despacho MX cerrado exitosamente con el estado: $nuevoEstadoDespacho y todos los datos actualizados.");
            }
        } else {
            // Opción por defecto para otros tipos de servicio
            return redirect('/iniciar')->with('message', "Despacho cerrado exitosamente con el estado: $nuevoEstadoDespacho y todos los datos actualizados.");
        }
    }
    
}
