<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ComentariosReserva;
use App\Models\FotosViaje;
use App\Models\LikesFoto;
use App\Models\Reservas;
use App\Models\Publicacion;
use App\Models\LikesPublicacion;
use App\Models\ComentarioPublicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
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

            // Obtener datos adicionales según el rol desde la relación userable
            $additionalData = [];
            if ($user->userable) {
                $additionalData = $user->userable->toArray();
            } elseif ($user->role === 'turista') {
                // Para turistas existentes sin userable, buscar en tabla usuarios
                $usuarioLegacy = \App\Models\Usuarios::where('Email', $user->email)->first();
                if ($usuarioLegacy) {
                    $additionalData = $usuarioLegacy->toArray();
                }
            }

            return response()->json([
                'success' => true,
                'perfil' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'telefono' => $user->telefono ?: $additionalData['Telefono'] ?? null,
                    'foto_perfil' => $user->foto_perfil ? asset('storage/' . $user->foto_perfil) : null,
                    'biografia' => $user->biografia,
                    'privacidad_perfil' => $user->privacidad_perfil,
                    'ultima_actividad' => $user->ultima_actividad,
                    'fecha_registro' => $user->created_at,
                    'total_fotos' => FotosViaje::where('id_usuario', $user->id)->count(),
                    'total_likes_recibidos' => FotosViaje::where('id_usuario', $user->id)->withCount('likes')->get()->sum('likes_count'),
                    'nombre' => $user->nombre ?: $additionalData['Nombre'] ?? 'No especificado',
                    'apellido' => $user->apellido ?: $additionalData['Apellido'] ?? 'No especificado',
                    'nacionalidad' => $additionalData['Nacionalidad'] ?? 'No especificada',
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
            /** @var \App\Models\User $user */
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
                'telefono' => 'nullable|string|max:20',
                'nombre' => 'nullable|string|max:255',
                'apellido' => 'nullable|string|max:255',
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

            if ($request->has('telefono')) {
                $data['telefono'] = $request->telefono;
            }

            if ($request->has('nombre')) {
                $data['nombre'] = $request->nombre;
            }

            if ($request->has('apellido')) {
                $data['apellido'] = $request->apellido;
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
                    'telefono' => $user->telefono,
                    'biografia' => $user->biografia,
                    'privacidad_perfil' => $user->privacidad_perfil,
                    'nombre' => $user->nombre,
                    'apellido' => $user->apellido,
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
            /** @var \App\Models\User $user */
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
            /** @var \App\Models\User $user */
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
            /** @var \App\Models\User $user */
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
            /** @var \App\Models\User $user */
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
     * Obtener estadísticas del perfil del usuario
     */
    public function obtenerEstadisticas()
    {
        try {
            /** @var \App\Models\User $user */
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

            // Obtener estadísticas detalladas de reservas
            $reservasTotal = Reservas::where('idUsuario', $usuarioReal->id)->count();
            $reservasConfirmadas = Reservas::where('idUsuario', $usuarioReal->id)
                                          ->where('Estado', 'confirmada')
                                          ->count();
            $reservasProximas = Reservas::where('idUsuario', $usuarioReal->id)
                                       ->where('Fecha_Reserva', '>', now())
                                       ->count();

            $fotos = FotosViaje::where('id_usuario', $user->id)->count();
            $comentarios = ComentariosReserva::where('id_usuario', $user->id)->count();

            return response()->json([
                'success' => true,
                'estadisticas' => [
                    'reservas_total' => $reservasTotal,
                    'reservas_confirmadas' => $reservasConfirmadas,
                    'reservas_proximas' => $reservasProximas,
                    'fotos' => $fotos,
                    'comentarios' => $comentarios
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Subir foto de perfil
     */
    public function subirFotoPerfil(Request $request)
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'foto_perfil' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Eliminar foto anterior si existe
            if ($user->foto_perfil && Storage::disk('public')->exists($user->foto_perfil)) {
                Storage::disk('public')->delete($user->foto_perfil);
            }

            $fotoPath = $request->file('foto_perfil')->store('fotos_perfil', 'public');

            $user->foto_perfil = $fotoPath;
            $user->ultima_actividad = now();
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto de perfil actualizada exitosamente',
                'foto_url' => asset('storage/' . $fotoPath)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al subir foto de perfil',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar foto de perfil
     */
    public function eliminarFotoPerfil()
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Eliminar foto si existe
            if ($user->foto_perfil && Storage::disk('public')->exists($user->foto_perfil)) {
                Storage::disk('public')->delete($user->foto_perfil);
            }

            $user->foto_perfil = null;
            $user->ultima_actividad = now();
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto de perfil eliminada exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar foto de perfil',
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
            /** @var \App\Models\User $user */
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

    /**
     * Obtener publicaciones del usuario
     */
    public function obtenerPublicaciones()
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            $publicaciones = Publicacion::where('id_usuario', $user->id)
                                      ->with(['usuario:id,name,foto_perfil', 'likes'])
                                      ->withCount('likes')
                                      ->orderBy('created_at', 'desc')
                                      ->get()
                                      ->map(function($publicacion) use ($user) {
                                          return [
                                              'id' => $publicacion->id,
                                              'titulo' => $publicacion->titulo,
                                              'descripcion' => $publicacion->descripcion,
                                              'imagen' => $publicacion->imagen ? asset('storage/' . $publicacion->imagen) : null,
                                              'privacidad' => $publicacion->privacidad,
                                              'fecha_creacion' => $publicacion->created_at,
                                              'usuario' => [
                                                  'id' => $publicacion->usuario->id,
                                                  'name' => $publicacion->usuario->name,
                                                  'foto_perfil' => $publicacion->usuario->foto_perfil ? asset('storage/' . $publicacion->usuario->foto_perfil) : null,
                                              ],
                                              'likes_count' => $publicacion->likes_count,
                                              'comentarios_count' => $publicacion->comentarios_count,
                                              'user_liked' => $publicacion->userLiked($user->id)
                                          ];
                                      });

            return response()->json([
                'success' => true,
                'publicaciones' => $publicaciones
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener publicaciones',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear nueva publicación
     */
    public function crearPublicacion(Request $request)
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'titulo' => 'required|string|max:255',
                'descripcion' => 'required|string|max:1000',
                'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
                'privacidad' => 'nullable|in:publico,privado'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $imagenPath = $request->file('imagen')->store('publicaciones', 'public');

            $publicacion = Publicacion::create([
                'id_usuario' => $user->id,
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'imagen' => $imagenPath,
                'privacidad' => $request->privacidad ?? 'publico'
            ]);

            $user->ultima_actividad = now();
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Publicación creada exitosamente',
                'publicacion' => $publicacion->load('usuario:id,name,foto_perfil')->loadCount('likes')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear publicación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar publicación
     */
    public function eliminarPublicacion($id)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            $publicacion = Publicacion::where('id', $id)
                                    ->where('id_usuario', $user->id)
                                    ->first();

            if (!$publicacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Publicación no encontrada'
                ], 404);
            }

            // Eliminar imagen si existe
            if ($publicacion->imagen && Storage::disk('public')->exists($publicacion->imagen)) {
                Storage::disk('public')->delete($publicacion->imagen);
            }

            $publicacion->delete();

            return response()->json([
                'success' => true,
                'message' => 'Publicación eliminada exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar publicación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dar/quitar like a una publicación
     */
    public function darLikePublicacion($id)
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            $publicacion = Publicacion::findOrFail($id);

            // Verificar si ya dio like
            $existingLike = LikesPublicacion::where('id_publicacion', $id)
                                           ->where('id_usuario', $user->id)
                                           ->first();

            if ($existingLike) {
                // Quitar like
                $existingLike->delete();
                $liked = false;
            } else {
                // Dar like
                LikesPublicacion::create([
                    'id_publicacion' => $id,
                    'id_usuario' => $user->id
                ]);
                $liked = true;
            }

            $user->ultima_actividad = now();
            $user->save();

            return response()->json([
                'success' => true,
                'liked' => $liked,
                'total_likes' => $publicacion->fresh()->likes()->count()
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
     * Obtener comentarios de una publicación
     */
    public function obtenerComentariosPublicacion($publicacionId)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            $comentarios = ComentarioPublicacion::where('id_publicacion', $publicacionId)
                                              ->with(['usuario:id,name,foto_perfil'])
                                              ->orderBy('created_at', 'asc')
                                              ->get()
                                              ->map(function($comentario) {
                                                  return [
                                                      'id' => $comentario->id,
                                                      'comentario' => $comentario->comentario,
                                                      'fecha_creacion' => $comentario->created_at,
                                                      'usuario' => [
                                                          'id' => $comentario->usuario->id,
                                                          'name' => $comentario->usuario->name,
                                                          'foto_perfil' => $comentario->usuario->foto_perfil ? asset('storage/' . $comentario->usuario->foto_perfil) : null,
                                                      ]
                                                  ];
                                              });

            return response()->json([
                'success' => true,
                'comentarios' => $comentarios
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener comentarios',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear comentario en una publicación
     */
    public function crearComentarioPublicacion(Request $request, $publicacionId)
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'comentario' => 'required|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verificar que la publicación existe
            $publicacion = Publicacion::findOrFail($publicacionId);

            $comentario = ComentarioPublicacion::create([
                'id_usuario' => $user->id,
                'id_publicacion' => $publicacionId,
                'comentario' => $request->comentario
            ]);

            $user->ultima_actividad = now();
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Comentario agregado exitosamente',
                'comentario' => [
                    'id' => $comentario->id,
                    'comentario' => $comentario->comentario,
                    'fecha_creacion' => $comentario->created_at,
                    'usuario' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'foto_perfil' => $user->foto_perfil ? asset('storage/' . $user->foto_perfil) : null,
                    ]
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear comentario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar comentario de una publicación
     */
    public function eliminarComentarioPublicacion($comentarioId)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            $comentario = ComentarioPublicacion::where('id', $comentarioId)
                                             ->where('id_usuario', $user->id)
                                             ->first();

            if (!$comentario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Comentario no encontrado o no tienes permisos para eliminarlo'
                ], 404);
            }

            $comentario->delete();

            return response()->json([
                'success' => true,
                'message' => 'Comentario eliminado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar comentario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener publicaciones públicas de otros usuarios
     */
    public function obtenerPublicacionesPublicas(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Obtener publicaciones públicas de otros usuarios (excluyendo las del usuario actual)
            $publicaciones = Publicacion::with(['usuario', 'likes', 'comentarios'])
                ->where('privacidad', 'publico')
                ->where('id_usuario', '!=', $user->id)
                ->whereHas('usuario', function($query) {
                    // Solo usuarios con perfil público
                    $query->where('privacidad_perfil', 'publico');
                })
                ->orderBy('created_at', 'desc')
                ->limit(20) // Limitar a 20 publicaciones para mejor rendimiento
                ->get();

            $publicacionesFormateadas = $publicaciones->map(function($publicacion) use ($user) {
                return [
                    'id' => $publicacion->id,
                    'titulo' => $publicacion->titulo,
                    'descripcion' => $publicacion->descripcion,
                    'imagen' => $publicacion->imagen ? asset('storage/' . $publicacion->imagen) : null,
                    'privacidad' => $publicacion->privacidad,
                    'fecha_creacion' => $publicacion->created_at,
                    'usuario' => [
                        'id' => $publicacion->usuario->id,
                        'name' => $publicacion->usuario->name,
                        'foto_perfil' => $publicacion->usuario->foto_perfil ? asset('storage/' . $publicacion->usuario->foto_perfil) : '/default-avatar.png'
                    ],
                    'likes_count' => $publicacion->likes->count(),
                    'comentarios_count' => $publicacion->comentarios->count(),
                    'user_liked' => $publicacion->likes->contains('id_usuario', $user->id)
                ];
            });

            return response()->json([
                'success' => true,
                'publicaciones' => $publicacionesFormateadas
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener publicaciones públicas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener todas las publicaciones para moderación (solo administradores)
     */
    public function obtenerTodasPublicaciones(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user || $user->role !== 'administrador') {
                return response()->json([
                    'success' => false,
                    'message' => 'Acceso no autorizado'
                ], 403);
            }

            $query = Publicacion::with(['usuario:id,name,email,foto_perfil', 'likes', 'comentarios'])
                               ->withCount('likes')
                               ->withCount('comentarios')
                               ->orderBy('created_at', 'desc');

            // Filtros opcionales
            if ($request->has('privacidad') && $request->privacidad !== '') {
                $query->where('privacidad', $request->privacidad);
            }

            if ($request->has('usuario_id')) {
                $query->where('id_usuario', $request->usuario_id);
            }

            $publicaciones = $query->paginate(20);

            $publicacionesFormateadas = $publicaciones->map(function($publicacion) {
                return [
                    'id' => $publicacion->id,
                    'titulo' => $publicacion->titulo,
                    'descripcion' => $publicacion->descripcion,
                    'imagen' => $publicacion->imagen ? asset('storage/' . $publicacion->imagen) : null,
                    'privacidad' => $publicacion->privacidad,
                    'fecha_creacion' => $publicacion->created_at,
                    'usuario' => [
                        'id' => $publicacion->usuario->id,
                        'name' => $publicacion->usuario->name,
                        'email' => $publicacion->usuario->email,
                        'foto_perfil' => $publicacion->usuario->foto_perfil ? asset('storage/' . $publicacion->usuario->foto_perfil) : '/default-avatar.png'
                    ],
                    'likes_count' => $publicacion->likes_count,
                    'comentarios_count' => $publicacion->comentarios_count,
                    'reportes_count' => 0 // Placeholder para sistema de reportes futuro
                ];
            });

            return response()->json([
                'success' => true,
                'publicaciones' => $publicacionesFormateadas,
                'pagination' => [
                    'current_page' => $publicaciones->currentPage(),
                    'last_page' => $publicaciones->lastPage(),
                    'per_page' => $publicaciones->perPage(),
                    'total' => $publicaciones->total()
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener publicaciones',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar publicación por moderador (solo administradores)
     */
    public function eliminarPublicacionModerador($id)
    {
        try {
            $user = Auth::user();

            if (!$user || $user->role !== 'administrador') {
                return response()->json([
                    'success' => false,
                    'message' => 'Acceso no autorizado'
                ], 403);
            }

            $publicacion = Publicacion::find($id);

            if (!$publicacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Publicación no encontrada'
                ], 404);
            }

            // Eliminar imagen si existe
            if ($publicacion->imagen && Storage::disk('public')->exists($publicacion->imagen)) {
                Storage::disk('public')->delete($publicacion->imagen);
            }

            // Eliminar likes y comentarios asociados
            $publicacion->likes()->delete();
            $publicacion->comentarios()->delete();

            $publicacion->delete();

            return response()->json([
                'success' => true,
                'message' => 'Publicación eliminada exitosamente por moderador'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar publicación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar perfil del usuario (eliminación completa de cuenta)
     */
    public function eliminarPerfil()
    {
        try {
            /** @var \App\Models\User $user */
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

            // Iniciar transacción para asegurar integridad de datos
            DB::beginTransaction();

            try {
                // 1. Eliminar reservas del usuario
                $reservas = Reservas::where('idUsuario', $usuarioReal->id)->get();
                foreach ($reservas as $reserva) {
                    // Eliminar comentarios de reservas
                    ComentariosReserva::where('id_reserva', $reserva->id)->delete();
                    // Eliminar la reserva
                    $reserva->delete();
                }

                // 2. Eliminar publicaciones del usuario
                $publicaciones = Publicacion::where('id_usuario', $user->id)->get();
                foreach ($publicaciones as $publicacion) {
                    // Eliminar imagen de la publicación
                    if ($publicacion->imagen && Storage::disk('public')->exists($publicacion->imagen)) {
                        Storage::disk('public')->delete($publicacion->imagen);
                    }
                    // Eliminar likes y comentarios de la publicación
                    $publicacion->likes()->delete();
                    $publicacion->comentarios()->delete();
                    // Eliminar la publicación
                    $publicacion->delete();
                }

                // 3. Eliminar fotos de viaje del usuario
                $fotos = FotosViaje::where('id_usuario', $user->id)->get();
                foreach ($fotos as $foto) {
                    // Eliminar imagen de la foto
                    if ($foto->ruta_imagen && Storage::disk('public')->exists($foto->ruta_imagen)) {
                        Storage::disk('public')->delete($foto->ruta_imagen);
                    }
                    // Eliminar likes de la foto
                    $foto->likes()->delete();
                    // Eliminar la foto
                    $foto->delete();
                }

                // 4. Eliminar foto de perfil del usuario
                if ($user->foto_perfil && Storage::disk('public')->exists($user->foto_perfil)) {
                    Storage::disk('public')->delete($user->foto_perfil);
                }

                // 5. Eliminar el usuario real de la tabla usuarios
                $usuarioReal->delete();

                // 6. Finalmente, eliminar el usuario de la tabla users
                $user->delete();

                // Confirmar transacción
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Perfil eliminado exitosamente. Todas tus reservas, publicaciones y datos han sido eliminados permanentemente.'
                ], 200);

            } catch (\Exception $e) {
                // Revertir transacción en caso de error
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el perfil',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener estadísticas de publicaciones para administradores
     */
    public function obtenerEstadisticasPublicaciones()
    {
        try {
            $user = Auth::user();

            if (!$user || $user->role !== 'administrador') {
                return response()->json([
                    'success' => false,
                    'message' => 'Acceso no autorizado'
                ], 403);
            }

            $totalPublicaciones = Publicacion::count();
            $publicacionesPublicas = Publicacion::where('privacidad', 'publico')->count();
            $publicacionesPrivadas = Publicacion::where('privacidad', 'privado')->count();
            $totalLikes = LikesPublicacion::count();
            $totalComentarios = ComentarioPublicacion::count();

            // Publicaciones por día (últimos 30 días)
            $publicacionesPorDia = Publicacion::selectRaw('DATE(created_at) as fecha, COUNT(*) as cantidad')
                                             ->where('created_at', '>=', now()->subDays(30))
                                             ->groupBy('fecha')
                                             ->orderBy('fecha')
                                             ->get();

            return response()->json([
                'success' => true,
                'estadisticas' => [
                    'total_publicaciones' => $totalPublicaciones,
                    'publicaciones_publicas' => $publicacionesPublicas,
                    'publicaciones_privadas' => $publicacionesPrivadas,
                    'total_likes' => $totalLikes,
                    'total_comentarios' => $totalComentarios,
                    'publicaciones_por_dia' => $publicacionesPorDia
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
