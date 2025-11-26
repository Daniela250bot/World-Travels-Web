<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LikesPublicacion extends Model
{
    protected $table = 'likes_publicaciones';

    protected $fillable = [
        'id_usuario',
        'id_publicacion'
    ];

    // Relación con usuario
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    // Relación con publicación
    public function publicacion(): BelongsTo
    {
        return $this->belongsTo(Publicacion::class, 'id_publicacion');
    }
}
