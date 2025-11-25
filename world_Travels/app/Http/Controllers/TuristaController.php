<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ComentariosReserva;
use App\Models\FotosViaje;
use App\Models\LikesFoto;
use App\Models\Reservas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class TuristaController extends Controller
{
    /**
     * Obtener perfil del usuario autenticado
     */
    public function obtenerPerfil()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            return response()->json([
                'success' => true,
                'perfil' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'foto_perfil' => $user->foto_perfil ? asset('storage/' . $user->foto_perfil) : null,
                    'biografia' => $user->biografia,
                    'privacidad_perfil' => $user->privacidad_perfil,
                    'ultima_actividad' => $user->ultima_actividad,
                    'fecha_registro' => $user->created_at,
                    'total_fotos' => FotosViaje::where('id_usuario', $user->id)->count(),
                    'total_likes_recibidos' => FotosViaje::where('id_usuario', $user->id)->withCount('likes')->get()->sum('likes_count'),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener perfil',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar perfil del usuario
     */
    public function actualizarPerfil(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'biografia' => 'nullable|string|max:500',
                'privacidad_perfil' => 'nullable|in:publico,privado',
                'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = [];

            // Manejar subida de foto de perfil
            if ($request->hasFile('foto_perfil')) {
                // Eliminar foto anterior si existe
                if ($user->foto_perfil && Storage::disk('public')->exists($user->foto_perfil)) {
                    Storage::disk('public')->delete($user->foto_perfil);
                }

                $fotoPath = $request->file('foto_perfil')->store('fotos_perfil', 'public');
                $data['foto_perfil'] = $fotoPath;
            }

            if ($request->has('biografia')) {
                $data['biografia'] = $request->biografia;
            }

            if ($request->has('privacidad_perfil')) {
                $data['privacidad_perfil'] = $request->privacidad_perfil;
            }

            $user->fill($data);
            $user->ultima_actividad = now();
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Perfil actualizado exitosamente',
                'perfil' => [
                    'foto_perfil' => $user->foto_perfil ? asset('storage/' . $user->foto_perfil) : null,
                    'name' => $user->name,
                    'biografia' => $user->biografia,
                    'privacidad_perfil' => $user->privacidad_perfil,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar perfil',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Agregar comentario a una reserva
     */
    public function agregarComentarioReserva(Request $request, $reservaId)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'comentario' => 'required|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verificar que la reserva existe y pertenece al usuario
            $reserva = Reservas::where('id', $reservaId)
                              ->where('idUsuario', $user->id)
                              ->first();

            if (!$reserva) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reserva no encontrada o no tienes permisos para comentarla'
                ], 404);
            }

            $comentario = ComentariosReserva::create([
                'id_reserva' => $reservaId,
                'id_usuario' => $user->id,
                'comentario' => $request->comentario
            ]);

            $user->ultima_actividad = now();
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Comentario agregado exitosamente',
                'comentario' => $comentario->load('usuario:id,name')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar comentario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener reservas del usuario (pasadas y próximas)
     */
    public function obtenerReservas()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Obtener el usuario real de la tabla usuarios
            $usuarioReal = $user->userable;

            if (!$usuarioReal || !($usuarioReal instanceof \App\Models\Usuarios)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado en el sistema'
                ], 404);
            }

            $reservas = Reservas::where('idUsuario', $usuarioReal->id)
                            ->with(['actividad:id,Nombre_Actividad,Fecha_Actividad,Hora_Actividad,Precio', 'comentarios.usuario:id,Nombre,Apellido'])
                            ->orderBy('Fecha_Reserva', 'desc')
                            ->get();

            $reservasPasadas = $reservas->filter(function($reserva) {
                return Carbon::parse($reserva->Fecha_Reserva)->isPast();
            });

            $reservasProximas = $reservas->filter(function($reserva) {
                return Carbon::parse($reserva->Fecha_Reserva)->isFuture();
            });

            return response()->json([
                'success' => true,
                'reservas' => [
                    'pasadas' => $reservasPasadas->values(),
                    'proximas' => $reservasProximas->values()
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener reservas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Subir foto de viaje
     */
    public function subirFotoViaje(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'titulo' => 'required|string|max:255',
                'descripcion' => 'nullable|string|max:1000',
                'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
                'privacidad' => 'nullable|in:publico,privado'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $imagenPath = $request->file('imagen')->store('fotos_viajes', 'public');

            $foto = FotosViaje::create([
                'id_usuario' => $user->id,
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'ruta_imagen' => $imagenPath,
                'privacidad' => $request->privacidad ?? 'publico'
            ]);

            $user->ultima_actividad = now();
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto subida exitosamente',
                'foto' => $foto->load('usuario:id,name')->loadCount('likes')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al subir foto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dar/quitar like a una foto
     */
    public function toggleLikeFoto($fotoId)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            $foto = FotosViaje::findOrFail($fotoId);

            // Verificar si ya dio like
            $existingLike = LikesFoto::where('id_foto_viaje', $fotoId)
                                    ->where('id_usuario', $user->id)
                                    ->first();

            if ($existingLike) {
                // Quitar like
                $existingLike->delete();
                $liked = false;
            } else {
                // Dar like
                LikesFoto::create([
                    'id_foto_viaje' => $fotoId,
                    'id_usuario' => $user->id
                ]);
                $liked = true;
            }

            $user->ultima_actividad = now();
            $user->save();

            return response()->json([
                'success' => true,
                'liked' => $liked,
                'total_likes' => $foto->fresh()->countLikes()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar like',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener fotos públicas (feed)
     */
    public function obtenerFotosPublicas(Request $request)
    {
        try {
            $user = Auth::user();

            $query = FotosViaje::with(['usuario:id,name,foto_perfil', 'likes'])
                              ->withCount('likes')
                              ->where('privacidad', 'publico')
                              ->orderBy('fecha_subida', 'desc');

            // Si hay usuario autenticado, incluir información de si dio like
            if ($user) {
                $query->leftJoin('likes_fotos', function($join) use ($user) {
                    $join->on('fotos_viajes.id', '=', 'likes_fotos.id_foto_viaje')
                         ->where('likes_fotos.id_usuario', '=', $user->id);
                })->select('fotos_viajes.*', 'likes_fotos.id_usuario as user_liked');
            }

            $fotos = $query->paginate(20);

            return response()->json([
                'success' => true,
                'fotos' => $fotos
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener fotos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener feed de actividades del usuario
     */
    public function obtenerFeedActividades()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            $actividades = collect();

            // Agregar fotos subidas
            $fotos = FotosViaje::where('id_usuario', $user->id)
                              ->withCount('likes')
                              ->orderBy('fecha_subida', 'desc')
                              ->take(10)
                              ->get()
                              ->map(function($foto) {
                                  return [
                                      'tipo' => 'foto_subida',
                                      'fecha' => $foto->fecha_subida,
                                      'titulo' => $foto->titulo,
                                      'descripcion' => $foto->descripcion,
                                      'imagen' => asset('storage/' . $foto->ruta_imagen),
                                      'likes_count' => $foto->likes_count
                                  ];
                              });

            // Agregar comentarios en reservas
            $comentarios = ComentariosReserva::where('id_usuario', $user->id)
                                           ->with('reserva.actividad:id,Nombre_Actividad')
                                           ->orderBy('fecha_comentario', 'desc')
                                           ->take(10)
                                           ->get()
                                           ->map(function($comentario) {
                                               return [
                                                   'tipo' => 'comentario_reserva',
                                                   'fecha' => $comentario->fecha_comentario,
                                                   'actividad' => $comentario->reserva->actividad->Nombre_Actividad ?? 'Actividad',
                                                   'comentario' => $comentario->comentario
                                               ];
                                           });

            // Combinar y ordenar por fecha
            $actividades = $fotos->concat($comentarios)
                                ->sortByDesc('fecha')
                                ->take(20)
                                ->values();

            return response()->json([
                'success' => true,
                'actividades' => $actividades
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener feed de actividades',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
