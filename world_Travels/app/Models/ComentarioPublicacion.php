<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComentarioPublicacion extends Model
{
    protected $table = 'comentarios_publicaciones';

    protected $fillable = [
        'id_usuario',
        'id_publicacion',
        'comentario'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
