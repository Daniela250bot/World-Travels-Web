<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Empresa extends Authenticatable implements JWTSubject
{
    protected $table = 'empresas';

    protected $fillable = [
        'user_id',
        'numero',
        'nombre',
        'direccion',
        'ciudad',
        'correo',
        'contraseña',
        'codigo_verificacion',
        'estado'
    ];

    protected $hidden = [
        'contraseña',
        'remember_token',
        'codigo_verificacion',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    // Removido porque la tabla empresas no tiene columna 'role'
    // protected $attributes = [
    //     'role' => 'empresas',
    // ];

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

    // Relación con User (uno a uno)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con empleados (usuarios asociados)
    public function empleados()
    {
        return $this->hasMany(Usuarios::class, 'empresa_id');
    }

    // Relación con actividades
    public function actividades()
    {
        return $this->hasMany(Actividades::class, 'empresa_id');
    }

    // Relación con permisos (si hay tabla intermedia)
    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'empresa_permisos', 'empresa_id', 'permiso_id');
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
            'user_id' => 'nullable|integer|exists:users,id',
            'numero' => 'nullable|string|max:20|unique:empresas,numero,' . $id,
            'nombre' => 'required|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:255',
            'correo' => 'required|string|email|max:255|unique:empresas,correo,' . $id,
            'contraseña' => 'nullable|string|min:8', // Hacer opcional para dashboard
            'codigo_verificacion' => 'nullable|string|size:8|unique:empresas,codigo_verificacion,' . $id
        ];
    }
}
