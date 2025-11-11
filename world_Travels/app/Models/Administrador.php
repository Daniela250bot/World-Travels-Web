<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\DB;

class Administrador extends Authenticatable implements JWTSubject
{
    protected $table = 'administradores';

    protected $fillable = [
        'nombre',
        'apellido',
        'correo_electronico',
        'telefono',
        'documento',
        'contraseña',
        'codigo_verificacion'
    ];

    protected $hidden = [
        'contraseña',
        'remember_token',
        'codigo_verificacion',
    ];

    // Métodos requeridos por JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // Método para obtener la contraseña (necesario para JWT)
    public function getAuthPassword()
    {
        return $this->contraseña;
    }

    // Método para obtener el identificador de autenticación
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    // Método para obtener el identificador de autenticación
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    // Relación con permisos
    public function permisos()
    {
        return Permiso::whereHas('roles', function($query) {
            $query->where('rol', 'administrador');
        })->get();
    }

    // Método para verificar si tiene un permiso específico
    public function tienePermiso($permisoNombre)
    {
        return $this->permisos()->where('nombre', $permisoNombre)->exists();
    }

    // Método para verificar si tiene permisos en un módulo
    public function tienePermisosEnModulo($modulo)
    {
        return $this->permisos()->where('modulo', $modulo)->exists();
    }

    // Método para obtener todos los permisos del administrador
    public function obtenerPermisos()
    {
        return $this->permisos;
    }

    // Método para generar código de verificación
    public static function generarCodigoVerificacion()
    {
        return strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
    }

    // Reglas de validación
    public static function rules($id = null)
    {
        return [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo_electronico' => 'required|string|email|max:255|unique:administradores,correo_electronico,' . $id,
            'telefono' => 'required|string|max:20',
            'documento' => 'required|string|max:20|unique:administradores,documento,' . $id,
            'contraseña' => $id ? 'nullable|string|min:8' : 'required|string|min:8',
            'codigo_verificacion' => 'required|string|size:8|unique:administradores,codigo_verificacion,' . $id
        ];
    }
}
