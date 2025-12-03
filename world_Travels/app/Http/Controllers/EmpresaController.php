<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Empresa::query();

            // Filtrar por categoría si se proporciona
            if ($request->has('categoria') && $request->categoria) {
                $query->whereHas('actividades', function($q) use ($request) {
                    $q->where('idCategoria', $request->categoria);
                });
            }

            $empresas = $query->get();
            return response()->json([
                'success' => true,
                'data' => $empresas,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener empresas',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación manual básica
        if (!$request->has('nombre') || empty($request->nombre)) {
            return response()->json([
                'success' => false,
                'errors' => ['nombre' => ['El nombre es requerido']],
            ], 422);
        }

        if (!$request->has('correo') || empty($request->correo)) {
            return response()->json([
                'success' => false,
                'errors' => ['correo' => ['El correo es requerido']],
            ], 422);
        }

        try {
            // Solo permitir campos específicos y válidos
            $data = [];

            if ($request->has('numero') && !empty($request->numero)) {
                $data['numero'] = $request->numero;
            }

            if ($request->has('nombre') && !empty($request->nombre)) {
                $data['nombre'] = $request->nombre;
            }

            if ($request->has('direccion') && !empty($request->direccion)) {
                $data['direccion'] = $request->direccion;
            }

            if ($request->has('ciudad') && !empty($request->ciudad)) {
                $data['ciudad'] = $request->ciudad;
            }

            if ($request->has('correo') && !empty($request->correo)) {
                $data['correo'] = $request->correo;
            }

            // Estado siempre true por defecto para nuevas empresas
            $data['estado'] = true;

            // Generar contraseña si no se proporciona (para dashboard)
            $password = $request->contraseña ?? 'password123';
            $hashedPassword = Hash::make($password);

            // Generar código de verificación si no se proporciona
            $data['codigo_verificacion'] = Empresa::generarCodigoVerificacion();

            // Crear o encontrar usuario en tabla users con rol 'empresa'
            $user = \App\Models\User::firstOrCreate(
                ['email' => $request->correo],
                [
                    'name' => $request->nombre,
                    'password' => $hashedPassword,
                    'role' => 'empresa',
                    'verificado' => true
                ]
            );

            // Agregar user_id a los datos de la empresa
            $data['user_id'] = $user->id;
            $data['contraseña'] = $hashedPassword;

            // Crear empresa con campos explícitos
            $empresa = new Empresa();
            $empresa->user_id = $data['user_id'] ?? null;
            $empresa->numero = $data['numero'] ?? null;
            $empresa->nombre = $data['nombre'];
            $empresa->direccion = $data['direccion'] ?? null;
            $empresa->ciudad = $data['ciudad'] ?? null;
            $empresa->correo = $data['correo'];
            $empresa->contraseña = $data['contraseña'];
            $empresa->codigo_verificacion = $data['codigo_verificacion'];
            $empresa->estado = $data['estado'];
            $empresa->save();

            return response()->json([
                'success' => true,
                'data' => $empresa,
                'message' => 'Empresa creada exitosamente',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear empresa',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $empresa = Empresa::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $empresa,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Empresa no encontrada',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $empresa = Empresa::findOrFail($id);

            $validator = Validator::make($request->all(), Empresa::rules($id));

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $data = $request->all();
            if ($request->has('contraseña') && !empty($request->contraseña)) {
                $data['contraseña'] = Hash::make($request->contraseña);
            } else {
                unset($data['contraseña']);
            }

            $empresa->update($data);

            return response()->json([
                'success' => true,
                'data' => $empresa,
                'message' => 'Empresa actualizada exitosamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar empresa',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle the status (active/blocked) of an empresa
     */
    public function toggleStatus($id)
    {
        try {
            $empresa = Empresa::findOrFail($id);

            $empresa->update([
                'estado' => !$empresa->estado
            ]);

            $statusText = $empresa->estado ? 'activada' : 'bloqueada';

            return response()->json([
                'success' => true,
                'message' => "Empresa {$statusText} exitosamente",
                'data' => $empresa,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar estado de empresa',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $empresa = Empresa::with('empleados', 'actividades.reservas')->findOrFail($id);

            // Validaciones de seguridad antes de eliminar
            $tieneEmpleados = $empresa->empleados->count() > 0;
            $tieneActividadesConReservas = $empresa->actividades->filter(function($actividad) {
                return $actividad->reservas->count() > 0;
            })->count() > 0;

            if ($tieneEmpleados || $tieneActividadesConReservas) {
                $errores = [];
                if ($tieneEmpleados) {
                    $errores[] = "La empresa tiene {$empresa->empleados->count()} empleado(s) asignado(s)";
                }
                if ($tieneActividadesConReservas) {
                    $actividadesConReservas = $empresa->actividades->filter(function($actividad) {
                        return $actividad->reservas->count() > 0;
                    });
                    $errores[] = "La empresa tiene {$actividadesConReservas->count()} actividad(es) con reservas activas";
                }

                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar la empresa debido a las siguientes restricciones:',
                    'restricciones' => $errores,
                ], 422);
            }

            // Si pasa las validaciones, proceder con la eliminación
            $empresa->delete();

            return response()->json([
                'success' => true,
                'message' => 'Empresa eliminada exitosamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar empresa',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Login para empresas
     */

   
public function login(Request $request)
{
    // Accept both 'contraseña' and 'password' for compatibility
    $data = $request->all();
    \Log::info('Login data received:', $data);

    // Handle different password field names due to encoding issues
    if (isset($data['contrasea']) && !isset($data['contraseña'])) {
        $data['contraseña'] = $data['contrasea'];
    }
    if (isset($data['password']) && !isset($data['contraseña'])) {
        $data['contraseña'] = $data['password'];
    }

    $validator = Validator::make($data, [
        'correo' => 'required|string|email',
    ]);

    // Validate password field (could be contraseña, contrasea, or password)
    $passwordField = null;
    if (isset($data['contraseña'])) {
        $passwordField = 'contraseña';
    } elseif (isset($data['contrasea'])) {
        $passwordField = 'contrasea';
    } elseif (isset($data['password'])) {
        $passwordField = 'password';
    }

    if (!$passwordField || empty($data[$passwordField]) || strlen($data[$passwordField]) < 8) {
        return response()->json([
            'success' => false,
            'errors' => [
                'password' => ['La contraseña es requerida y debe tener al menos 8 caracteres.']
            ],
        ], 422);
    }

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors(),
        ], 422);
    }

    // Get credentials with the correct field names for authentication
    $credentials = [
        'correo' => $data['correo'],
        'password' => $data['contrasea'] ?: $data['password'] ?? $data['contraseña']
    ];

    try {
        if (!$token = Auth::guard('api-empresas')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales inválidas',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'token' => $token,
            'empresa' => Auth::guard('api-empresas')->user(), // 💡 aquí estaba el error
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error en la autenticación',
            'error' => $e->getMessage(),
        ], 500);
    }
}



    /**
     * Logout para empresas
     */
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'success' => true,
                'message' => 'Sesión cerrada exitosamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cerrar sesión',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtener información de la empresa autenticada
     */
    public function me()
    {
        try {
            $empresa = Auth::guard('api-empresas')->user();

            if (!$empresa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Empresa no autenticada',
                ], 401);
            }

            // Verificar que la empresa existe en la base de datos
            $empresaVerificada = Empresa::find($empresa->id);
            if (!$empresaVerificada) {
                return response()->json([
                    'success' => false,
                    'message' => 'Empresa no encontrada en la base de datos',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'empresa' => [
                    'id' => $empresa->id,
                    'numero' => $empresa->numero,
                    'nombre' => $empresa->nombre,
                    'descripcion' => $empresa->descripcion,
                    'direccion' => $empresa->direccion,
                    'ciudad' => $empresa->ciudad,
                    'correo' => $empresa->correo,
                    'telefono' => $empresa->telefono,
                    'sitio_web' => $empresa->sitio_web,
                    'user_id' => $empresa->user_id,
                    'fecha_registro' => $empresa->created_at,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener información de la empresa',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Actualizar el perfil de la empresa autenticada
     */
    public function actualizarMiPerfil(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numero' => 'nullable|string|max:20|unique:empresas,numero,' . Auth::guard('api-empresas')->id(),
            'nombre' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'sitio_web' => 'nullable|url|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $empresa = Auth::guard('api-empresas')->user();

            if (!$empresa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Empresa no autenticada',
                ], 401);
            }

            // Verificar que el usuario autenticado sea efectivamente una empresa
            if (!($empresa instanceof Empresa)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acceso denegado. Solo empresas pueden acceder a esta ruta',
                ], 403);
            }

            $empresa->update($request->only([
                'numero', 'nombre', 'descripcion', 'direccion',
                'ciudad', 'telefono', 'sitio_web'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Perfil actualizado exitosamente',
                'empresa' => $empresa,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el perfil',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Listar empleados de la empresa autenticada
     */
    public function listarEmpleados()
    {
        try {
            $empresa = Auth::guard('api-empresas')->user();

            if (!$empresa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Empresa no autenticada',
                ], 401);
            }

            $empleados = $empresa->empleados()->with('user')->get();

            $empleadosFormateados = $empleados->map(function($empleado) {
                return [
                    'id' => $empleado->id,
                    'nombre' => $empleado->Nombre . ' ' . $empleado->Apellido,
                    'email' => $empleado->Email,
                    'telefono' => $empleado->Telefono,
                    'rol' => $empleado->Rol,
                    'fecha_registro' => $empleado->created_at,
                ];
            });

            return response()->json([
                'success' => true,
                'empleados' => $empleadosFormateados,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al listar empleados',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remover empleado de la empresa
     */
    public function removerEmpleado($usuarioId)
    {
        try {
            $empresa = Auth::guard('api-empresas')->user();

            if (!$empresa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Empresa no autenticada',
                ], 401);
            }

            $empleado = $empresa->empleados()->findOrFail($usuarioId);

            $empleado->update([
                'empresa_id' => null,
                'Rol' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Empleado removido exitosamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al remover empleado',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Asignar empleado a empresa
     */
    public function asignarEmpleado(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'usuario_id' => 'required|exists:usuarios,id',
            'rol' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $empresa = Auth::guard('api-empresas')->user();

            if (!$empresa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Empresa no autenticada',
                ], 401);
            }

            $usuario = \App\Models\Usuarios::findOrFail($request->usuario_id);

            $usuario->update([
                'empresa_id' => $empresa->id,
                'Rol' => $request->rol,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Empleado asignado exitosamente',
                'data' => $usuario,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al asignar empleado',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtener empleados de una empresa
     */
    public function empleados($empresaId)
    {
        try {
            $empresa = Empresa::findOrFail($empresaId);
            $empleados = $empresa->empleados;

            return response()->json([
                'success' => true,
                'data' => $empleados,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener empleados',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generar reporte detallado de empresa
     */
    public function reporte($empresaId)
    {
        try {
            $empresa = Empresa::with(['empleados', 'actividades.reservas', 'actividades.categoria', 'actividades.municipio'])->findOrFail($empresaId);

            $actividades = $empresa->actividades->map(function($actividad) {
                $reservasCount = $actividad->reservas->count();
                $ingresos = $reservasCount * $actividad->Precio;
                return [
                    'id' => $actividad->id,
                    'nombre' => $actividad->Nombre_Actividad,
                    'fecha' => $actividad->Fecha_Actividad,
                    'hora' => $actividad->Hora_Actividad,
                    'precio' => $actividad->Precio,
                    'cupo_maximo' => $actividad->Cupo_Maximo,
                    'ubicacion' => $actividad->Ubicacion,
                    'categoria' => $actividad->categoria ? $actividad->categoria->nombre : 'N/A',
                    'municipio' => $actividad->municipio ? $actividad->municipio->Nombre_Municipio : 'N/A',
                    'reservas_count' => $reservasCount,
                    'ingresos' => $ingresos,
                    'ocupacion_porcentaje' => $actividad->Cupo_Maximo > 0 ? round(($reservasCount / $actividad->Cupo_Maximo) * 100, 2) : 0,
                ];
            });

            $empleados = $empresa->empleados->map(function($empleado) {
                return [
                    'id' => $empleado->id,
                    'nombre' => $empleado->Nombre . ' ' . $empleado->Apellido,
                    'email' => $empleado->Email,
                    'rol' => $empleado->Rol,
                    'telefono' => $empleado->Telefono,
                ];
            });

            $totalReservas = $actividades->sum('reservas_count');
            $totalIngresos = $actividades->sum('ingresos');
            $promedioOcupacion = $actividades->avg('ocupacion_porcentaje');

            $reporte = [
                'empresa' => [
                    'id' => $empresa->id,
                    'numero' => $empresa->numero,
                    'nombre' => $empresa->nombre,
                    'descripcion' => $empresa->descripcion,
                    'direccion' => $empresa->direccion,
                    'ciudad' => $empresa->ciudad,
                    'correo' => $empresa->correo,
                    'telefono' => $empresa->telefono,
                    'sitio_web' => $empresa->sitio_web,
                    'fecha_registro' => $empresa->created_at,
                ],
                'estadisticas' => [
                    'total_empleados' => $empleados->count(),
                    'total_actividades' => $actividades->count(),
                    'total_reservas' => $totalReservas,
                    'total_ingresos' => $totalIngresos,
                    'promedio_ocupacion' => round($promedioOcupacion, 2),
                    'actividades_activas' => $actividades->where('fecha', '>=', now()->toDateString())->count(),
                ],
                'actividades' => $actividades,
                'empleados' => $empleados,
                'resumen_mensual' => $this->generarResumenMensual($actividades),
            ];

            return response()->json([
                'success' => true,
                'data' => $reporte,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar reporte',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generar resumen mensual de actividades
     */
    private function generarResumenMensual($actividades)
    {
        $resumen = [];
        $actividadesPorMes = $actividades->groupBy(function($actividad) {
            return Carbon::parse($actividad['fecha'])->format('Y-m');
        });

        foreach ($actividadesPorMes as $mes => $acts) {
            $resumen[$mes] = [
                'mes' => $mes,
                'actividades' => $acts->count(),
                'reservas' => $acts->sum('reservas_count'),
                'ingresos' => $acts->sum('ingresos'),
            ];
        }

        return array_values($resumen);
    }

    /**
     * Listar actividades de la empresa autenticada
     */
    public function listarActividades()
    {
        try {
            $empresa = Auth::guard('api-empresas')->user();

            if (!$empresa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Empresa no autenticada',
                ], 401);
            }
            $actividades = $empresa->actividades()->with('categoria', 'municipio', 'reservas')->get();

            $actividadesFormateadas = $actividades->map(function($actividad) {
                return [
                    'id' => $actividad->id,
                    'nombre' => $actividad->Nombre_Actividad,
                    'descripcion' => $actividad->Descripcion,
                    'fecha' => $actividad->Fecha_Actividad,
                    'hora' => $actividad->Hora_Actividad,
                    'precio' => $actividad->Precio,
                    'cupo_maximo' => $actividad->Cupo_Maximo,
                    'ubicacion' => $actividad->Ubicacion,
                    'imagen' => $actividad->Imagen,
                    'categoria' => $actividad->categoria ? $actividad->categoria->nombre : 'N/A',
                    'municipio' => $actividad->municipio ? $actividad->municipio->Nombre_Municipio : 'N/A',
                    'total_reservas' => $actividad->reservas->count(),
                    'disponibilidad' => $actividad->Cupo_Maximo - $actividad->reservas->count(),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $actividadesFormateadas,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al listar actividades',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Crear actividad para la empresa autenticada
     */
    public function crearActividad(Request $request, $empresaId = null)
    {
        $validator = Validator::make($request->all(), [
            'idCategoria' => 'required|integer|exists:categories,id',
            'idMunicipio' => 'required|integer|exists:municipios,id',
            'Nombre_Actividad' => 'required|string|max:255',
            'Descripcion' => 'required|string',
            'Fecha_Actividad' => 'required|date|after:today',
            'Hora_Actividad' => 'required|string',
            'Precio' => 'required|numeric|min:0',
            'Cupo_Maximo' => 'required|integer|min:1',
            'Ubicacion' => 'required|string|max:255',
            'Imagen' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Obtener la empresa directamente del token JWT
            $empresa = Auth::guard('api-empresas')->user();

            if (!$empresa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Empresa no autenticada',
                ], 401);
            }

            // Verificar que la empresa existe en la base de datos (doble verificación)
            $empresaVerificada = Empresa::find($empresa->id);
            if (!$empresaVerificada) {
                return response()->json([
                    'success' => false,
                    'message' => 'Empresa no encontrada en la base de datos',
                ], 404);
            }

            $data = $request->all();
            $data['empresa_id'] = $empresa->id;
            // Asignar el usuario de la empresa como creador
            $data['idUsuario'] = $empresa->user_id ?? 1;

            $actividad = \App\Models\Actividades::create($data);

            return response()->json([
                'success' => true,
                'data' => $actividad,
                'message' => 'Actividad creada exitosamente',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear actividad: ' . $e->getMessage(),
                'line' => $e->getLine(),
                'file' => basename($e->getFile()),
            ], 500);
        }
    }

    /**
     * Ver detalles de una actividad de la empresa autenticada
     */
    public function verActividad($actividadId)
    {
        try {
            $empresa = Auth::guard('api-empresas')->user();
            if (!$empresa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Empresa no autenticada',
                ], 401);
            }

            // Verificar que el usuario autenticado sea efectivamente una empresa
            // if (!($empresa instanceof Empresa)) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Acceso denegado. Solo empresas pueden acceder a esta ruta',
            //     ], 403);
            // }

            $actividad = $empresa->actividades()->with('categoria', 'municipio', 'reservas.usuario')->findOrFail($actividadId);

            return response()->json([
                'success' => true,
                'data' => $actividad,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Actividad no encontrada',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Actualizar actividad de la empresa autenticada
     */
    public function actualizarActividad(Request $request, $actividadId)
    {
        $validator = Validator::make($request->all(), [
            'idCategoria' => 'nullable|integer|exists:categories,id',
            'idMunicipio' => 'nullable|integer|exists:municipios,id',
            'Nombre_Actividad' => 'nullable|string|max:255',
            'Descripcion' => 'nullable|string',
            'Fecha_Actividad' => 'nullable|date',
            'Hora_Actividad' => 'nullable|string',
            'Precio' => 'nullable|numeric|min:0',
            'Cupo_Maximo' => 'nullable|integer|min:1',
            'Ubicacion' => 'nullable|string|max:255',
            'Imagen' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $empresa = Auth::guard('api-empresas')->user();
            if (!$empresa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Empresa no autenticada',
                ], 401);
            }

            // Verificar que el usuario autenticado sea efectivamente una empresa
            // if (!($empresa instanceof Empresa)) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Acceso denegado. Solo empresas pueden acceder a esta ruta',
            //     ], 403);
            // }

            $actividad = $empresa->actividades()->findOrFail($actividadId);

            $actividad->update($request->all());

            return response()->json([
                'success' => true,
                'data' => $actividad,
                'message' => 'Actividad actualizada exitosamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar actividad',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Eliminar actividad de la empresa autenticada
     */
    public function eliminarActividad($actividadId)
    {
        try {
            $empresa = Auth::guard('api-empresas')->user();
            if (!$empresa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Empresa no autenticada',
                ], 401);
            }

            // Verificar que el usuario autenticado sea efectivamente una empresa
            // if (!($empresa instanceof Empresa)) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Acceso denegado. Solo empresas pueden acceder a esta ruta',
            //     ], 403);
            // }

            $actividad = $empresa->actividades()->findOrFail($actividadId);

            // Verificar si tiene reservas activas
            $reservasActivas = $actividad->reservas()->where('Estado', 'confirmada')->count();
            if ($reservasActivas > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar la actividad porque tiene reservas confirmadas',
                ], 422);
            }

            $actividad->delete();

            return response()->json([
                'success' => true,
                'message' => 'Actividad eliminada exitosamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar actividad',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Listar reservas de actividades de la empresa autenticada
     */
    public function listarReservas()
    {
        try {
            $empresa = Auth::guard('api-empresas')->user();
            if (!$empresa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Empresa no autenticada',
                ], 401);
            }

            // Verificar que el usuario autenticado sea efectivamente una empresa
            // if (!($empresa instanceof Empresa)) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Acceso denegado. Solo empresas pueden acceder a esta ruta',
            //     ], 403);
            // }

            $reservas = \App\Models\Reservas::whereHas('actividad', function($query) use ($empresa) {
                $query->where('empresa_id', $empresa->id);
            })->with('actividad', 'usuario')->get();

            $reservasFormateadas = $reservas->map(function($reserva) {
                return [
                    'id' => $reserva->id,
                    'fecha_reserva' => $reserva->Fecha_Reserva,
                    'numero_personas' => $reserva->Numero_Personas,
                    'estado' => $reserva->Estado,
                    'actividad' => [
                        'id' => $reserva->actividad->id,
                        'nombre' => $reserva->actividad->Nombre_Actividad,
                        'fecha' => $reserva->actividad->Fecha_Actividad,
                        'precio' => $reserva->actividad->Precio,
                    ],
                    'usuario' => [
                        'id' => $reserva->usuario->id,
                        'nombre' => $reserva->usuario->Nombre . ' ' . $reserva->usuario->Apellido,
                        'email' => $reserva->usuario->Email,
                    ],
                    'total' => $reserva->Numero_Personas * $reserva->actividad->Precio,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $reservasFormateadas,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al listar reservas',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Confirmar reserva
     */
    public function confirmarReserva($reservaId)
    {
        try {
            $empresa = Auth::guard('api-empresas')->user();
            if (!$empresa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Empresa no autenticada',
                ], 401);
            }

            $reserva = \App\Models\Reservas::whereHas('actividad', function($query) use ($empresa) {
                $query->where('empresa_id', $empresa->id);
            })->findOrFail($reservaId);

            $reserva->update(['Estado' => 'confirmada']);

            return response()->json([
                'success' => true,
                'message' => 'Reserva confirmada exitosamente',
                'data' => $reserva,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al confirmar reserva',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cancelar reserva
     */
    public function cancelarReserva($reservaId)
    {
        try {
            $empresa = Auth::guard('api-empresas')->user();
            if (!$empresa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Empresa no autenticada',
                ], 401);
            }

            $reserva = \App\Models\Reservas::whereHas('actividad', function($query) use ($empresa) {
                $query->where('empresa_id', $empresa->id);
            })->findOrFail($reservaId);

            $reserva->update(['Estado' => 'cancelada']);

            return response()->json([
                'success' => true,
                'message' => 'Reserva cancelada exitosamente',
                'data' => $reserva,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar reserva',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Crear actividad usando rutas estándar (para empresas)
     */
    public function crearActividadEmpresa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idCategoria' => 'required|integer|exists:categories,id',
            'idMunicipio' => 'required|integer|exists:municipios,id',
            'Nombre_Actividad' => 'required|string|max:255',
            'Descripcion' => 'required|string',
            'Fecha_Actividad' => 'required|date|after:today',
            'Hora_Actividad' => 'required|string',
            'Precio' => 'required|numeric|min:0',
            'Cupo_Maximo' => 'required|integer|min:1',
            'Ubicacion' => 'required|string|max:255',
            'Imagen' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $empresa = Auth::guard('api-empresas')->user();

            if (!$empresa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Empresa no autenticada',
                ], 401);
            }

            // Verificar que el usuario autenticado sea efectivamente una empresa
            if (!($empresa instanceof Empresa)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acceso denegado. Solo empresas pueden acceder a esta ruta',
                ], 403);
            }

            // Verificar que la empresa existe en la base de datos
            $empresaVerificada = Empresa::find($empresa->id);
            if (!$empresaVerificada) {
                return response()->json([
                    'success' => false,
                    'message' => 'Empresa no encontrada en la base de datos',
                ], 404);
            }

            $data = $request->all();
            $data['empresa_id'] = $empresa->id;
            $data['idUsuario'] = $empresa->user_id ?? 1;

            $actividad = \App\Models\Actividades::create($data);

            return response()->json([
                'success' => true,
                'data' => $actividad,
                'message' => 'Actividad creada exitosamente',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear actividad: ' . $e->getMessage(),
                'line' => $e->getLine(),
                'file' => basename($e->getFile()),
            ], 500);
        }
    }

    /**
     * Actualizar actividad usando rutas estándar (para empresas)
     */
    public function actualizarActividadEmpresa(Request $request, $actividadId)
    {
        $validator = Validator::make($request->all(), [
            'idCategoria' => 'nullable|integer|exists:categories,id',
            'idMunicipio' => 'nullable|integer|exists:municipios,id',
            'Nombre_Actividad' => 'nullable|string|max:255',
            'Descripcion' => 'nullable|string',
            'Fecha_Actividad' => 'nullable|date',
            'Hora_Actividad' => 'nullable|string',
            'Precio' => 'nullable|numeric|min:0',
            'Cupo_Maximo' => 'nullable|integer|min:1',
            'Ubicacion' => 'nullable|string|max:255',
            'Imagen' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $empresa = Auth::guard('api-empresas')->user();
            if (!$empresa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Empresa no autenticada',
                ], 401);
            }

            // Verificar que el usuario autenticado sea efectivamente una empresa
            if (!($empresa instanceof Empresa)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acceso denegado. Solo empresas pueden acceder a esta ruta',
                ], 403);
            }

            $actividad = $empresa->actividades()->findOrFail($actividadId);

            $actividad->update($request->all());

            return response()->json([
                'success' => true,
                'data' => $actividad,
                'message' => 'Actividad actualizada exitosamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar actividad',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Eliminar actividad usando rutas estándar (para empresas)
     */
    public function eliminarActividadEmpresa($actividadId)
    {
        try {
            $empresa = Auth::guard('api-empresas')->user();
            if (!$empresa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Empresa no autenticada',
                ], 401);
            }

            // Verificar que el usuario autenticado sea efectivamente una empresa
            if (!($empresa instanceof Empresa)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acceso denegado. Solo empresas pueden acceder a esta ruta',
                ], 403);
            }

            $actividad = $empresa->actividades()->findOrFail($actividadId);

            // Verificar si tiene reservas activas
            $reservasActivas = $actividad->reservas()->where('Estado', 'confirmada')->count();
            if ($reservasActivas > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar la actividad porque tiene reservas confirmadas',
                ], 422);
            }

            $actividad->delete();

            return response()->json([
                'success' => true,
                'message' => 'Actividad eliminada exitosamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar actividad',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
