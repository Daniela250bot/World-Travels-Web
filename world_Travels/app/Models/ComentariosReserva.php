<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComentariosReserva extends Model
{
    protected $table = 'comentarios_reservas';

    protected $fillable = [
        'id_reserva',
        'id_usuario',
        'comentario',
        'calificacion',
        'aprobado',
        'fecha_comentario'
    ];

    protected $casts = [
        'fecha_comentario' => 'datetime'
    ];

    // Relación con la reserva
    public function reserva()
    {
        return $this->belongsTo(Reservas::class, 'id_reserva');
    }

    // Relación con el usuario que hizo el comentario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    // Relación con las fotos de la reseña
    public function fotos()
    {
        return $this->hasMany(FotosViaje::class, 'id_comentario_reserva');
    }
}
