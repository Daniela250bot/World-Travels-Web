<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Publicacion extends Model
{
    protected $table = 'publicaciones';

    protected $fillable = [
        'id_usuario',
        'titulo',
        'descripcion',
        'imagen',
        'privacidad'
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

    // Relación con likes
    public function likes(): HasMany
    {
        return $this->hasMany(LikesPublicacion::class, 'id_publicacion');
    }

    // Relación con comentarios
    public function comentarios(): HasMany
    {
        return $this->hasMany(ComentarioPublicacion::class, 'id_publicacion');
    }

    // Método para verificar si el usuario actual dio like
    public function userLiked($userId)
    {
        return $this->likes()->where('id_usuario', $userId)->exists();
    }

    // Método para obtener el conteo de likes
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    // Método para obtener el conteo de comentarios
    public function getComentariosCountAttribute()
    {
        return $this->comentarios()->count();
    }
}
