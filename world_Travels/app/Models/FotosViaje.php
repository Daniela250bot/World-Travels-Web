<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotosViaje extends Model
{
    protected $table = 'fotos_viajes';

    protected $fillable = [
        'id_usuario',
        'id_comentario_reserva',
        'titulo',
        'descripcion',
        'ruta_imagen',
        'privacidad',
        'fecha_subida'
    ];

    protected $casts = [
        'fecha_subida' => 'datetime'
    ];

    // Relación con el usuario que subió la foto
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    // Relación con el comentario de reserva (para reseñas)
    public function comentarioReserva()
    {
        return $this->belongsTo(ComentariosReserva::class, 'id_comentario_reserva');
    }

    // Relación con los likes de esta foto
    public function likes()
    {
        return $this->hasMany(LikesFoto::class, 'id_foto_viaje');
    }

    // Método para contar likes
    public function countLikes()
    {
        return $this->likes()->count();
    }

    // Método para verificar si un usuario dio like
    public function hasLikeFrom($userId)
    {
        return $this->likes()->where('id_usuario', $userId)->exists();
    }
}
