<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Permiso extends Model
{
    protected $table = 'permisos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'modulo'
    ];

    // Método para verificar si el permiso pertenece a un rol específico
    public function perteneceARol($rol)
    {
        return DB::table('roles_permisos')->where('permiso_id', $this->id)->where('rol', $rol)->exists();
    }
}
