<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'imagen',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    // Relación con actividades
    public function actividades()
    {
        return $this->hasMany(Actividades::class, 'idCategoria');
    }

    // Scope para categorías activas
    public function scopeActivas($query)
    {
        return $query->where('estado', true);
    }
}
