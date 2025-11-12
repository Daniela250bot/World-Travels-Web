<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Usuarios extends Authenticatable implements JWTSubject
{
    protected $table = 'usuarios';

    protected $fillable = [
        'user_id',
        'Nombre',
        'Apellido',
        'Email',
        'Contraseña',
        'Telefono',
        'Nacionalidad',
        'Fecha_Registro',
        'Rol',
        'codigo_verificacion'
    ];

    protected $hidden = [
        'Contraseña',
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

     public function reservas()
    {
        return $this->hasMany(Reservas::class, 'idUsuario');
    }

    // Relación con User (uno a uno)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación: un usuario puede crear muchas actividades
    public function actividades()
    {
        return $this->hasMany(Actividades::class, 'idUsuario');
    }

    // Relación: un usuario puede hacer muchos comentarios
    public function comentarios()
    {
        return $this->hasMany(Comentarios::class, 'idUsuario');
    }

    // Método para obtener la contraseña (necesario para JWT)
    public function getAuthPassword()
    {
        return $this->Contraseña;
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


    // Método para generar código de verificación (no usado para turistas)
    public static function generarCodigoVerificacion()
    {
        return strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
    }

    // Reglas de validación
    public static function rules($id = null)
    {
        return [
            'Nombre' => 'required|string|max:255',
            'Apellido' => 'required|string|max:255',
            'Email' => 'required|string|email|max:255|unique:usuarios,Email,' . $id,
            'Contraseña' => $id ? 'nullable|string|min:8' : 'required|string|min:8',
            'Telefono' => 'required|string|max:20',
            'Nacionalidad' => 'required|string|max:255',
            'Rol' => 'required|in:Turista,Guía Turístico,Administrador'
        ];
    }
}
