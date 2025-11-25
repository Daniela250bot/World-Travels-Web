<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ComentariosReserva;
use App\Models\Reservas;
use App\Models\FotosViaje;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ComentariosReservasController extends Controller
{
    // Listar comentarios de reservas
    public function index(Request $request)
    {
        $query = ComentariosReserva::with(['reserva', 'usuario', 'fotos'])->where('aprobado', true);

        if ($request->has('reserva_id')) {
            $query->where('id_reserva', $request->reserva_id);
        }

        if ($request->has('actividad_id')) {
            $query->whereHas('reserva', function($q) use ($request) {
                $q->where('idActividad', $request->actividad_id);
            });
        }

        $comentarios = $query->get();
        return response()->json($comentarios);
    }

    // Crear un comentario de reserva (reseña)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_reserva' => 'required|integer|exists:reservas,id',
            'comentario' => 'required|string',
            'calificacion' => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $reserva = Reservas::find($request->id_reserva);

        // Verificar que la reserva pertenece al usuario autenticado
        if ($reserva->idUsuario != Auth::id()) {
            return response()->json(['message' => 'No autorizado para comentar esta reserva'], 403);
        }

        // Verificar que la fecha de la reserva ya pasó (asistió)
        $fechaReserva = Carbon::parse($reserva->Fecha_Reserva . ' ' . ($reserva->hora ?? '23:59:59'));
        if ($fechaReserva->isFuture()) {
            return response()->json(['message' => 'Solo puedes dejar reseñas después de asistir a la actividad'], 400);
        }

        // Verificar que no haya ya un comentario para esta reserva
        $existing = ComentariosReserva::where('id_reserva', $request->id_reserva)->first();
        if ($existing) {
            return response()->json(['message' => 'Ya has dejado una reseña para esta reserva'], 400);
        }

        $comentario = ComentariosReserva::create([
            'id_reserva' => $request->id_reserva,
            'id_usuario' => Auth::id(),
            'comentario' => $request->comentario,
            'calificacion' => $request->calificacion,
            'fecha_comentario' => now(),
            'aprobado' => false, // Pendiente de moderación
        ]);

        return response()->json($comentario, 201);
    }

    // Mostrar un comentario
    public function show(string $id)
    {
        $comentario = ComentariosReserva::with(['reserva', 'usuario', 'fotos'])->find($id);

        if (!$comentario) {
            return response()->json(['message' => 'Comentario no encontrado'], 404);
        }

        return response()->json($comentario);
    }

    // Actualizar comentario (solo por el autor)
    public function update(Request $request, string $id)
    {
        $comentario = ComentariosReserva::find($id);

        if (!$comentario) {
            return response()->json(['message' => 'Comentario no encontrado'], 404);
        }

        if ($comentario->id_usuario != Auth::id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'comentario' => 'string',
            'calificacion' => 'integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $comentario->update($validator->validated());
        return response()->json($comentario);
    }

    // Aprobar comentario (para moderadores/admin)
    public function aprobar(string $id)
    {
        $comentario = ComentariosReserva::find($id);

        if (!$comentario) {
            return response()->json(['message' => 'Comentario no encontrado'], 404);
        }

        $comentario->update(['aprobado' => true]);
        return response()->json(['message' => 'Comentario aprobado']);
    }

    // Eliminar comentario
    public function destroy(string $id)
    {
        $comentario = ComentariosReserva::find($id);

        if (!$comentario) {
            return response()->json(['message' => 'Comentario no encontrado'], 404);
        }

        if ($comentario->id_usuario != Auth::id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $comentario->delete();
        return response()->json(['message' => 'Comentario eliminado']);
    }

    // Subir foto para una reseña
    public function subirFoto(Request $request, string $idComentario)
    {
        $comentario = ComentariosReserva::find($idComentario);

        if (!$comentario) {
            return response()->json(['message' => 'Comentario no encontrado'], 404);
        }

        if ($comentario->id_usuario != Auth::id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'titulo' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $path = $request->file('foto')->store('fotos_viajes', 'public');

        $foto = FotosViaje::create([
            'id_usuario' => Auth::id(),
            'id_comentario_reserva' => $idComentario,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'ruta_imagen' => $path,
            'privacidad' => 'publico',
            'fecha_subida' => now(),
        ]);

        return response()->json($foto, 201);
    }
}
