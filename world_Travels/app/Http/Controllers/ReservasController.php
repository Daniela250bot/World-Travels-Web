<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservas;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservaConfirmadaMail;
use App\Mail\NuevaReservaEmpresaMail;
use App\Mail\NuevaReservaAdministradorMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReservasController extends Controller
{
    // Listar todas las reservas
    public function index()
    {
        $reservas = Reservas::all();
        return response()->json($reservas);
    }

    // Crear una reserva
    public function store(Request $request)
    {
        Log::info('Iniciando creación de reserva', [
            'request_data' => $request->all(),
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip()
        ]);

        $validator = Validator::make($request->all(), [
            'idUsuario'        => 'required|integer|exists:usuarios,id',
            'idActividad'      => 'required|integer|exists:actividades,id',
            'Numero_Personas'  => 'required|integer|min:1|max:10',
            'Estado'           => 'required|string|in:pendiente,confirmada,cancelada',
            'notas'            => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            Log::warning('Validación fallida en reserva', [
                'errors' => $validator->errors(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Datos de reserva inválidos'
            ], 422);
        }

        try {
            // Obtener la actividad con relaciones
            $actividad = \App\Models\Actividades::with('categoria')->find($request->idActividad);
            if (!$actividad) {
                return response()->json([
                    'success' => false,
                    'message' => 'Actividad no encontrada'
                ], 404);
            }

            // Verificar que la actividad esté disponible (no cancelada o completada)
            if ($actividad->Estado === 'cancelada') {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta actividad ha sido cancelada'
                ], 400);
            }

            // Verificar fecha y hora - no permitir reservas para actividades pasadas
            $fechaActividad = Carbon::parse($actividad->Fecha_Actividad . ' ' . $actividad->Hora_Actividad);
            $now = Carbon::now();

            if ($fechaActividad->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pueden hacer reservas para actividades que ya han pasado'
                ], 400);
            }

            // Verificar que el usuario existe
            $user = \App\Models\User::find($request->idUsuario);
            if (!$user) {
                Log::error('Usuario no encontrado en tabla users', [
                    'idUsuario' => $request->idUsuario,
                    'request_data' => $request->all()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            Log::info('Usuario encontrado', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_name' => $user->name
            ]);

            // Verificar si el idUsuario enviado es ya un ID de perfil de turista
            $usuario = \App\Models\Usuarios::find($request->idUsuario);

            if (!$usuario) {
                // Si no es un ID directo, buscar por user_id
                $usuario = \App\Models\Usuarios::where('user_id', $request->idUsuario)->first();

                if (!$usuario) {
                    // Verificar si ya existe un usuario con el mismo email
                    $usuarioExistente = \App\Models\Usuarios::where('Email', $user->email)->first();
                    if ($usuarioExistente) {
                        // Si existe, actualizar el user_id para vincularlo
                        $usuarioExistente->update(['user_id' => $request->idUsuario]);
                        $usuario = $usuarioExistente;
                        Log::info('Perfil de turista existente vinculado', [
                            'usuario_id' => $usuario->id,
                            'user_id' => $request->idUsuario,
                            'email' => $user->email
                        ]);
                    } else {
                        // Si no existe, crear uno nuevo
                        $usuario = \App\Models\Usuarios::create([
                            'user_id' => $request->idUsuario,
                            'Nombre' => $user->name ?? 'Usuario',
                            'Apellido' => '',
                            'Email' => $user->email ?? '',
                            'Contraseña' => Hash::make('temporal123'),
                            'Telefono' => '',
                            'Nacionalidad' => '',
                            'Fecha_Registro' => now(),
                            'Rol' => 'Turista',
                            'codigo_verificacion' => null,
                        ]);
                        Log::info('Nuevo perfil de turista creado', [
                            'usuario_id' => $usuario->id,
                            'user_id' => $request->idUsuario,
                            'email' => $user->email
                        ]);
                    }
                }
            }

            // Verificar que el usuario no tenga reservas activas para esta actividad
            $reservaExistente = Reservas::where('idUsuario', $usuario->id)
                ->where('idActividad', $request->idActividad)
                ->whereIn('Estado', ['pendiente', 'confirmada'])
                ->first();

            if ($reservaExistente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya tienes una reserva activa para esta actividad',
                    'reserva_existente' => $reservaExistente->id
                ], 400);
            }

            // Verificar capacidad disponible
            $reservasConfirmadas = Reservas::where('idActividad', $request->idActividad)
                ->where('Estado', 'confirmada')
                ->sum('Numero_Personas');

            $cupoDisponible = $actividad->Cupo_Maximo - $reservasConfirmadas;

            if ($cupoDisponible < $request->Numero_Personas) {
                return response()->json([
                    'success' => false,
                    'message' => "No hay cupo suficiente. Cupo disponible: {$cupoDisponible} personas",
                    'cupo_disponible' => $cupoDisponible,
                    'cupo_maximo' => $actividad->Cupo_Maximo
                ], 400);
            }

            // Preparar datos para la reserva
            $reservaData = [
                'idUsuario' => $usuario->id,
                'idActividad' => $request->idActividad,
                'Fecha_Reserva' => $actividad->Fecha_Actividad,
                'hora' => $actividad->Hora_Actividad,
                'Numero_Personas' => $request->Numero_Personas,
                'Estado' => $request->Estado,
                'notas' => $request->notas,
            ];

            Log::info('Creando reserva con datos', [
                'reserva_data' => $reservaData,
                'usuario_id' => $usuario->id,
                'actividad_id' => $actividad->id
            ]);

            // Crear la reserva
            $reserva = Reservas::create($reservaData);

            Log::info('Reserva creada exitosamente', [
                'reserva_id' => $reserva->id,
                'reserva_data' => $reserva->toArray()
            ]);

            // Notificar a la empresa sobre la nueva reserva
            Log::info('📧 Iniciando envío de notificaciones...');
            try {
                Log::info('🏢 Enviando notificación a empresa...');
                $this->notificarEmpresaNuevaReserva($reserva);
                Log::info('✅ Notificación a empresa enviada');
            } catch (\Exception $e) {
                Log::error('❌ Error notificando a empresa: ' . $e->getMessage());
            }

            // Notificar a los administradores sobre la nueva reserva
            try {
                Log::info('👥 Enviando notificaciones a administradores...');
                $this->notificarAdministradoresNuevaReserva($reserva);
                Log::info('✅ Notificaciones a administradores enviadas');
            } catch (\Exception $e) {
                Log::error('❌ Error notificando a administradores: ' . $e->getMessage());
            }

            // Enviar email de confirmación al turista si la reserva es confirmada
            if ($reserva->Estado === 'confirmada') {
                try {
                    Mail::to($reserva->usuario->email)->send(new ReservaConfirmadaMail($reserva));
                } catch (\Exception $e) {
                    Log::error('Error enviando email de confirmación al turista: ' . $e->getMessage());
                }
            }

            Log::info('✅ Reserva procesada exitosamente', [
                'reserva_id' => $reserva->id,
                'usuario_id' => $usuario->id,
                'actividad_id' => $actividad->id,
                'numero_personas' => $request->Numero_Personas,
                'estado' => $request->Estado,
                'cupo_restante' => $cupoDisponible - $request->Numero_Personas
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reserva creada exitosamente',
                'reserva' => $reserva->load('actividad', 'usuario'),
                'cupo_restante' => $cupoDisponible - $request->Numero_Personas
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creando reserva: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor al crear la reserva',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Mostrar una reserva por ID
    public function show(string $id)
    {
        $reserva = Reservas::find($id);

        if (!$reserva) {
            return response()->json(['message' => 'Reserva no encontrada'], 404);
        }

        return response()->json($reserva);
    }

    // Actualizar una reserva
    public function update(Request $request, string $id)
    {
        $reserva = Reservas::with('actividad')->find($id);

        if (!$reserva) {
            return response()->json([
                'success' => false,
                'message' => 'Reserva no encontrada'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'Numero_Personas'  => 'sometimes|integer|min:1|max:10',
            'Estado'           => 'sometimes|string|in:pendiente,confirmada,cancelada',
            'notas'            => 'sometimes|nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Datos de actualización inválidos'
            ], 422);
        }

        try {
            $oldEstado = $reserva->Estado;
            $oldPersonas = $reserva->Numero_Personas;

            $updateData = $validator->validated();

            // Si se está cambiando el número de personas, verificar capacidad
            if (isset($updateData['Numero_Personas']) && $updateData['Numero_Personas'] !== $oldPersonas) {
                $reservasConfirmadas = Reservas::where('idActividad', $reserva->idActividad)
                    ->where('Estado', 'confirmada')
                    ->where('id', '!=', $reserva->id) // Excluir la reserva actual
                    ->sum('Numero_Personas');

                $cupoDisponible = $reserva->actividad->Cupo_Maximo - $reservasConfirmadas;

                if ($cupoDisponible < $updateData['Numero_Personas']) {
                    return response()->json([
                        'success' => false,
                        'message' => "No hay cupo suficiente para {$updateData['Numero_Personas']} personas. Cupo disponible: {$cupoDisponible}",
                        'cupo_disponible' => $cupoDisponible
                    ], 400);
                }
            }

            // Verificar que no se pueda cambiar reservas de actividades pasadas
            if (isset($updateData['Estado']) && $updateData['Estado'] !== $oldEstado) {
                $fechaActividad = Carbon::parse($reserva->Fecha_Reserva . ' ' . $reserva->hora);
                if ($fechaActividad->isPast()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se pueden modificar reservas de actividades que ya han pasado'
                    ], 400);
                }
            }

            $reserva->update($updateData);

            // Enviar email si cambió a confirmada
            if ($oldEstado !== 'confirmada' && $reserva->Estado === 'confirmada') {
                try {
                    Mail::to($reserva->usuario->email)->send(new ReservaConfirmadaMail($reserva));
                } catch (\Exception $e) {
                    Log::error('Error enviando email de confirmación: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Reserva actualizada exitosamente',
                'reserva' => $reserva->load('actividad', 'usuario')
            ]);

        } catch (\Exception $e) {
            Log::error('Error actualizando reserva: ' . $e->getMessage(), [
                'reserva_id' => $id,
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor al actualizar la reserva'
            ], 500);
        }
    }

    // Cancelar una reserva (solo si es más de 24 horas antes)
    public function cancelar(string $id)
    {
        $reserva = Reservas::with('actividad', 'usuario')->find($id);

        if (!$reserva) {
            return response()->json([
                'success' => false,
                'message' => 'Reserva no encontrada'
            ], 404);
        }

        // Verificar si la reserva ya está cancelada
        if ($reserva->Estado === 'cancelada') {
            return response()->json([
                'success' => false,
                'message' => 'La reserva ya está cancelada'
            ], 400);
        }

        // Verificar si la actividad ya pasó
        $fechaActividad = Carbon::parse($reserva->Fecha_Reserva . ' ' . ($reserva->hora ?? '00:00:00'));
        if ($fechaActividad->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'No se pueden cancelar reservas de actividades que ya han pasado'
            ], 400);
        }

        // Verificar si es más de 24 horas antes
        $now = Carbon::now();
        if ($fechaActividad->diffInHours($now) < 24) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede cancelar menos de 24 horas antes de la actividad',
                'horas_restantes' => $fechaActividad->diffInHours($now)
            ], 400);
        }

        try {
            $reserva->update(['Estado' => 'cancelada']);

            // TODO: Podríamos enviar email de cancelación aquí

            return response()->json([
                'success' => true,
                'message' => 'Reserva cancelada con éxito',
                'reserva' => $reserva
            ]);

        } catch (\Exception $e) {
            Log::error('Error cancelando reserva: ' . $e->getMessage(), [
                'reserva_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor al cancelar la reserva'
            ], 500);
        }
    }

    // Obtener reservas del usuario autenticado
    public function misReservas(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Encontrar el perfil de turista
            $usuario = \App\Models\Usuarios::where('user_id', $user->id)->first();
            if (!$usuario) {
                return response()->json([
                    'success' => true,
                    'reservas' => [
                        'pasadas' => [],
                        'proximas' => []
                    ]
                ]);
            }

            $reservas = Reservas::with(['actividad.categoria', 'usuario'])
                ->where('idUsuario', $usuario->id)
                ->orderBy('Fecha_Reserva', 'desc')
                ->orderBy('hora', 'desc')
                ->get();

            $now = Carbon::now();

            $reservasPasadas = $reservas->filter(function ($reserva) use ($now) {
                $fechaActividad = Carbon::parse($reserva->Fecha_Reserva . ' ' . ($reserva->hora ?? '00:00:00'));
                return $fechaActividad->isPast();
            });

            $reservasProximas = $reservas->filter(function ($reserva) use ($now) {
                $fechaActividad = Carbon::parse($reserva->Fecha_Reserva . ' ' . ($reserva->hora ?? '00:00:00'));
                return $fechaActividad->isFuture();
            });

            return response()->json([
                'success' => true,
                'reservas' => [
                    'pasadas' => $reservasPasadas->values(),
                    'proximas' => $reservasProximas->values()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error obteniendo reservas del usuario: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor al obtener reservas'
            ], 500);
        }
    }

    // Verificar disponibilidad de una actividad
    public function verificarDisponibilidad(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idActividad' => 'required|integer|exists:actividades,id',
            'numero_personas' => 'required|integer|min:1|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $actividad = \App\Models\Actividades::find($request->idActividad);

            $reservasConfirmadas = Reservas::where('idActividad', $request->idActividad)
                ->where('Estado', 'confirmada')
                ->sum('Numero_Personas');

            $cupoDisponible = $actividad->Cupo_Maximo - $reservasConfirmadas;
            $disponible = $cupoDisponible >= $request->numero_personas;

            return response()->json([
                'success' => true,
                'disponible' => $disponible,
                'cupo_disponible' => $cupoDisponible,
                'cupo_maximo' => $actividad->Cupo_Maximo,
                'personas_solicitadas' => $request->numero_personas
            ]);

        } catch (\Exception $e) {
            Log::error('Error verificando disponibilidad: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    // Eliminar una reserva
    public function destroy(string $id)
    {
        $reserva = Reservas::find($id);

        if (!$reserva) {
            return response()->json(['message' => 'Reserva no encontrada para eliminar'], 404);
        }

        $reserva->delete();
        return response()->json(['message' => 'Reserva eliminada con éxito']);
    }

    /**
     * Notificar a la empresa sobre una nueva reserva
     */
    private function notificarEmpresaNuevaReserva(Reservas $reserva)
    {
        try {
            // Obtener la empresa asociada a la actividad
            $actividad = $reserva->actividad;
            if ($actividad && $actividad->empresa && $actividad->empresa->correo) {
                Mail::to($actividad->empresa->correo)->send(new NuevaReservaEmpresaMail($reserva));
                Log::info('Notificación enviada a empresa', [
                    'empresa_id' => $actividad->empresa->id,
                    'empresa_email' => $actividad->empresa->correo,
                    'reserva_id' => $reserva->id
                ]);
            } else {
                Log::warning('No se pudo notificar a la empresa: actividad sin empresa asociada o sin email', [
                    'actividad_id' => $actividad->id ?? null,
                    'reserva_id' => $reserva->id
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error notificando a empresa sobre nueva reserva: ' . $e->getMessage(), [
                'reserva_id' => $reserva->id,
                'actividad_id' => $reserva->idActividad
            ]);
            // No lanzamos la excepción para no interrumpir el flujo de reserva
        }
    }

    /**
     * Notificar a todos los administradores sobre una nueva reserva
     */
    private function notificarAdministradoresNuevaReserva(Reservas $reserva)
    {
        try {
            // Obtener todos los administradores con email
            $administradores = \App\Models\Administrador::whereNotNull('correo_electronico')
                ->where('correo_electronico', '!=', '')
                ->get();

            if ($administradores->isEmpty()) {
                Log::warning('No hay administradores para notificar sobre nueva reserva', [
                    'reserva_id' => $reserva->id
                ]);
                return;
            }

            $emailsEnviados = 0;
            foreach ($administradores as $admin) {
                try {
                    Mail::to($admin->correo_electronico)->send(new NuevaReservaAdministradorMail($reserva));
                    $emailsEnviados++;
                } catch (\Exception $e) {
                    Log::error('Error enviando notificación a administrador', [
                        'admin_id' => $admin->id,
                        'admin_email' => $admin->correo_electronico,
                        'reserva_id' => $reserva->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            Log::info('Notificaciones enviadas a administradores', [
                'reserva_id' => $reserva->id,
                'total_admins' => $administradores->count(),
                'emails_enviados' => $emailsEnviados
            ]);

        } catch (\Exception $e) {
            Log::error('Error obteniendo administradores para notificación: ' . $e->getMessage(), [
                'reserva_id' => $reserva->id
            ]);
            // No lanzamos la excepción para no interrumpir el flujo de reserva
        }
    }
}
