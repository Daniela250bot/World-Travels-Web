<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Municipios;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class MunicipiosController extends Controller
{
    public function index()
    {
          $municipios = Municipios::all();
        return response()->json($municipios);
    }
   
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
        'Nombre_Municipio'=> 'required|string|max:255|unique:municipios,Nombre_Municipio',
        'idDepartamento'=> 'required|integer|exists:departamentos,id',

        ]);

        if ($validator->fails()) {
          Log::warning('Validación fallida al crear municipio: ' . json_encode($validator->errors()));
          return response()->json([
              'success' => false,
              'errors' => $validator->errors(),
          ], 422);
         }

        try {
            $municipio = Municipios::create($validator->validated());
            Log::info('Municipio creado: ' . $municipio->id);
            return response()->json([
                'success' => true,
                'data' => $municipio,
                'message' => 'Municipio creado exitosamente',
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creando municipio: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
            ], 500);
        }
    }

    public function show(string $id)
     {
        try {
            $municipio = Municipios::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $municipio,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Municipio no encontrado',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function update(Request $request, string $id)
     {
        try {
            $municipio = Municipios::findOrFail($id);

            $validator = Validator::make($request->all(),[
            'Nombre_Municipio'=> 'nullable|string|max:255|unique:municipios,Nombre_Municipio,' . $id,
            'idDepartamento'=> 'nullable|integer|exists:departamentos,id',
           ]);

            if ($validator->fails()) {
                Log::warning('Validación fallida al actualizar municipio ID ' . $id . ': ' . json_encode($validator->errors()));
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $municipio->update($validator->validated());
            Log::info('Municipio ID ' . $id . ' actualizado exitosamente');
            return response()->json([
                'success' => true,
                'data' => $municipio,
                'message' => 'Municipio actualizado exitosamente',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error actualizando municipio ID ' . $id . ': ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar municipio',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

     public function destroy (string $id)
    {
        try {
            $municipio = Municipios::findOrFail($id);
            $municipio->delete();
            return response()->json([
                'success' => true,
                'message' => 'Municipio eliminado exitosamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar municipio',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
     
}
