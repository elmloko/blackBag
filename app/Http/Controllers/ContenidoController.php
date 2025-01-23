<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contenido;
use App\Models\Saca;
use App\Models\Eventos;
use Illuminate\Support\Facades\Http;

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
            'nropaquetesbl' => 'nullable|string',
            'nropaquetesems' => 'nullable|string',
            'nropaquetescp' => 'nullable|integer',
            'nropaquetesco' => 'nullable|integer',
            'nropaquetessn' => 'nullable|integer',
            'nropaquetessu' => 'nullable|integer',
            'nropaqueteset' => 'nullable|integer',
            'nropaquetesii' => 'nullable|integer',
            'nropaquetesof' => 'nullable|integer',
        ]);

        // Procesar manifiesto para nropaquetesems (API 1)
        $codigoManifiestoEms = $request->input('nropaquetesems');
        if ($codigoManifiestoEms) {
            $this->procesarManifiestoApi1($codigoManifiestoEms, $request, 'nropaquetesems');
        }

        // Procesar manifiesto para nropaquetesems (API 1)
        $codigoManifiestoBL = $request->input('nropaquetesbl');
        if ($codigoManifiestoBL) {
            $this->procesarManifiestoApi2($codigoManifiestoBL, $request, 'nropaquetesbl');
        }

        // Crear el nuevo contenido en la base de datos
        Contenido::create($request->all());

        // Recuperar la saca relacionada
        $saca = Saca::find($request->saca_id);
        if ($saca) {
            // Calcular la suma de nropaquetes
            $totalPaquetes = collect([
                $request->nropaquetesro,
                $request->nropaquetesbl,
                $request->nropaquetesems,
                $request->nropaquetescp,
                $request->nropaquetesco,
                $request->nropaquetessn,
                $request->nropaquetessu,
                $request->nropaqueteset,
                $request->nropaquetesii,
                $request->nropaquetesof,
            ])->filter()->sum();

            // Actualizar el total en la saca
            $saca->nropaquetes = $totalPaquetes;
            $saca->save();

            // Registrar evento en la tabla Eventos
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

    private function procesarManifiestoApi1($codigoManifiesto, &$request, $campo)
    {
        $url = "http://172.65.10.52:8011/api/admisiones/manifiesto?manifiesto=$codigoManifiesto";

        try {
            $response = Http::get($url);

            if ($response->successful()) {
                $jsonData = $response->json();

                if (isset($jsonData['data'])) {
                    $cantidad = count($jsonData['data']);
                    $request->merge([$campo => $cantidad]);

                    // Incrementar el campo 'listas' en +1
                    $listas = $request->input('listas', 0); // Si 'listas' es nulo, inicia con 0
                    $request->merge(['listas' => $listas + 1]);
                }
            } else {
                return redirect()->back()->with('message', 'Error al consultar la API EMS para el manifiesto.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('message', 'Excepción en la API EMS para el manifiesto: ' . $e->getMessage());
        }
    }

    private function procesarManifiestoApi2($codigoManifiesto, &$request, $campo)
    {
        $url = "https://correos.gob.bo:8000/api/searchbymanifiesto?manifiesto=$codigoManifiesto";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer eZMlItx6mQMNZjxoijEvf7K3pYvGGXMvEHmQcqvtlAPOEAPgyKDVOpyF7JP0ilbK'
            ])->withOptions([
                'verify' => false,
            ])->get($url);

            if ($response->successful()) {
                $jsonData = $response->json();

                if (isset($jsonData['data'])) {
                    $cantidad = count($jsonData['data']);
                    $request->merge([$campo => $cantidad]);

                    // Incrementar el campo 'listas' en +1
                    $listas = $request->input('listas', 0); // Si 'listas' es nulo, inicia con 0
                    $request->merge(['listas' => $listas + 1]);
                }
            } else {
                return redirect()->back()->with('message', 'Error al consultar la API TrackingBO para el manifiesto.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('message', 'Excepción en la API TrackingBO para el manifiesto: ' . $e->getMessage());
        }
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
            'nropaquetesbl' => 'nullable|string',
            'nropaquetesems' => 'nullable|string',
            'nropaquetescp' => 'nullable|integer',
            'nropaquetesco' => 'nullable|integer',
            'nropaquetessn' => 'nullable|integer',
            'nropaquetessu' => 'nullable|integer',
            'nropaqueteset' => 'nullable|integer',
            'nropaquetesii' => 'nullable|integer',
            'nropaquetesof' => 'nullable|integer',
        ]);
    
        // Procesar manifiesto para nropaquetesems (API 1)
        $codigoManifiestoEms = $request->input('nropaquetesems');
        if ($codigoManifiestoEms) {
            $this->procesarManifiestoApi1($codigoManifiestoEms, $request, 'nropaquetesems');
        }
    
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
    
        // Calcular la suma de los paquetes
        $totalPaquetes = collect([
            $request->nropaquetesro,
            $request->nropaquetesbl,
            $request->nropaquetesems,
            $request->nropaquetescp,
            $request->nropaquetesco,
            $request->nropaquetessn,
            $request->nropaquetessu,
            $request->nropaqueteset,
            $request->nropaquetesii,
            $request->nropaquetesof,
        ])->filter()->sum();
    
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
