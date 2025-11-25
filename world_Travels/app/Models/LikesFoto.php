<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LikesFoto extends Model
{
    protected $table = 'likes_fotos';

    protected $fillable = [
        'id_foto_viaje',
        'id_usuario'
    ];

    // Relación con la foto
    public function foto()
    {
        return $this->belongsTo(FotosViaje::class, 'id_foto_viaje');
    }

    // Relación con el usuario que dio like
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
