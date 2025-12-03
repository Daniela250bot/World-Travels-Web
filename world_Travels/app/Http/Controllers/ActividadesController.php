<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Actividades;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class ActividadesController extends Controller
{
    public function index(Request $request)
    {
        $query = Actividades::with('categoria', 'municipio');

        // Filtrar por categoría si se proporciona
        if ($request->has('categoria') && $request->categoria) {
            $query->where('idCategoria', $request->categoria);
        }

        // Filtrar por empresa si se proporciona
        if ($request->has('empresa') && $request->empresa) {
            $query->where('idUsuario', $request->empresa);
        }

        // Filtrar actividades de administradores (idUsuario NULL)
        if ($request->has('admin') && $request->admin) {
            $query->whereNull('idUsuario');
        }

        $actividades = $query->get();
        return response()->json($actividades);
    }

    public function store(Request $request)
    {
        try {
            // Log de debug para ver qué datos llegan
            Log::info('=== INICIO STORE ACTIVIDADES ===');
            Log::info('Datos recibidos en store actividades:', $request->all());
            Log::info('Headers recibidos:', $request->headers->all());
            Log::info('URL: ' . $request->fullUrl());
            Log::info('Método: ' . $request->method());

            // Verificar si hay token en la request
            $authHeader = $request->header('Authorization');
            Log::info('Authorization header:', ['present' => !empty($authHeader), 'value' => $authHeader]);

            // Verificar primero si es administrador para ajustar las reglas de validación
            $isAdmin = false;
            try {
                $user = JWTAuth::parseToken()->authenticate();
                $isAdmin = $user->role === 'administrador';
            } catch (\Exception $e) {
                // Si no se puede autenticar, continuar con validación normal
            }

            // Ajustar reglas de validación según el tipo de usuario
            $rules = [
                'idCategoria'  => 'required|integer|exists:categories,id',
                'idMunicipio'  => 'required|integer|exists:municipios,id',
                'Nombre_Actividad' => 'required|string|max:255',
                'Descripcion'  => 'required|string',
                'Fecha_Actividad' => 'required|date',
                'Hora_Actividad' => 'required|string',
                'Precio'       => 'required|numeric|min:0',
                'Cupo_Maximo'  => 'required|integer|min:1',
                'Ubicacion'    => 'required|string|max:255',
                'Imagen'       => 'nullable|string'
            ];

            // Para administradores, idUsuario es completamente opcional
            // Para otros usuarios, idUsuario es opcional pero debe existir en la tabla usuarios si se proporciona
            if ($isAdmin) {
                $rules['idUsuario'] = 'nullable|integer';
            } else {
                $rules['idUsuario'] = 'nullable|integer|exists:usuarios,id';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                Log::error('Errores de validación:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Error de validación en los datos proporcionados',
                    'user_type' => $isAdmin ? 'administrador' : 'usuario_normal'
                ], 422);
            }

            $data = $validator->validated();
            Log::info('Datos validados:', $data);

            // Asignar el usuario autenticado si no se proporciona y no es administrador
            if (!isset($data['idUsuario']) || !$data['idUsuario']) {
                Log::info('No se proporcionó idUsuario, intentando obtener usuario autenticado...');
                
                try {
                    $user = JWTAuth::parseToken()->authenticate();
                    Log::info('Usuario autenticado:', [
                        'id' => $user->id,
                        'email' => $user->email,
                        'role' => $user->role ?? 'NULL',
                        'userable_type' => $user->userable_type ?? 'NULL',
                        'userable_id' => $user->userable_id ?? 'NULL'
                    ]);
                    
                    // Solo asignar idUsuario si NO es administrador
                    if ($user->role !== 'administrador') {
                        // Si el usuario es de la tabla users, buscar el correspondiente en usuarios
                        if ($user->userable_type === 'App\\Models\\Usuarios') {
                            $data['idUsuario'] = $user->userable_id;
                            Log::info('Asignado idUsuario desde relación polimórfica:', $data['idUsuario']);
                        } else {
                            // Si no hay relación polimórfica, usar el ID directamente (para compatibilidad)
                            $data['idUsuario'] = $user->id;
                            Log::info('Asignado idUsuario directamente:', $data['idUsuario']);
                        }
                    } else {
                        // Para administradores, no asignar idUsuario (permitir NULL)
                        Log::info('Usuario es administrador, no se asigna idUsuario (permitir NULL)');
                        unset($data['idUsuario']); // Asegurar que idUsuario no esté en los datos
                    }
                } catch (\Exception $authException) {
                    Log::error('Error de autenticación:', [
                        'message' => $authException->getMessage(),
                        'trace' => $authException->getTraceAsString()
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Error de autenticación: ' . $authException->getMessage(),
                        'error_code' => 'AUTH_ERROR'
                    ], 401);
                }
            } else {
                Log::info('Se proporcionó idUsuario directamente:', $data['idUsuario']);
            }

            Log::info('Datos finales para crear actividad:', $data);
            $actividad = Actividades::create($data);
            Log::info('Actividad creada exitosamente:', $actividad->toArray());
            
            return response()->json([
                'success' => true,
                'message' => 'Actividad creada exitosamente',
                'actividad' => $actividad
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error en store actividades:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la actividad: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'error_code' => 'GENERAL_ERROR'
            ], 500);
        }
    }

    public function show(string $id)
    {
        $actividad = Actividades::with('categoria', 'usuario', 'municipio')->find($id);

        if (!$actividad) {
            return response()->json(['message' => 'Actividad no encontrada'], 404);
        }

        return response()->json($actividad);
    }

    public function update(Request $request, string $id)
    {
        $actividad = Actividades::find($id);

        if (!$actividad) {
            return response()->json(['message' => 'Actividad no encontrada para editar'], 404);
        }

        $validator = Validator::make($request->all(), [
            'Nombre_Actividad' => 'nullable|string|max:255',
            'Descripcion'  => 'nullable|string',
            'Fecha_Actividad' => 'nullable|date',
            'Hora_Actividad' => 'nullable|string',
            'Precio'       => 'nullable|numeric|min:0',
            'Cupo_Maximo'  => 'nullable|integer|min:1',
            'Ubicacion'    => 'nullable|string|max:255',
            'Imagen'       => 'nullable|string',
            'idCategoria'  => 'nullable|integer|exists:categories,id',
            'idMunicipio'  => 'nullable|integer|exists:municipios,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $actividad->update($validator->validated());
        return response()->json($actividad);
    }

    public function destroy(string $id)
    {
        $actividad = Actividades::find($id);

        if (!$actividad) {
            return response()->json(['message' => 'Actividad no encontrada para eliminar'], 404);
        }

        $actividad->delete();
        return response()->json(['message' => 'Actividad eliminada con éxito']);
    }
}
