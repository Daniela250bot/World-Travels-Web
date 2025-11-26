<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'userable_type',
        'userable_id',
        'codigo_verificacion',
        'codigo_verificacion_expires_at',
        'verificado',
        'fcm_token',
        'foto_perfil',
        'biografia',
        'privacidad_perfil',
        'telefono',
        'nombre',
        'apellido',
        'ultima_actividad',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'codigo_verificacion',
        'codigo_verificacion_expires_at',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'codigo_verificacion_expires_at' => 'datetime',
            'verificado' => 'boolean',
            'password' => 'hashed',
        ];
    }

    // Métodos requeridos por JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role,
            'verificado' => $this->verificado,
        ];
    }

    // Relación polimórfica
    public function userable()
    {
        return $this->morphTo();
    }

    // Método para obtener la contraseña (necesario para JWT)
    public function getAuthPassword()
    {
        return $this->password;
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

    // Método para verificar permisos basado en el rol
    public function tienePermiso($permisoNombre)
    {
        // Si es administrador, verificar permisos en la tabla roles_permisos
        if ($this->role === 'administrador') {
            return DB::table('roles_permisos')
                ->join('permisos', 'roles_permisos.permiso_id', '=', 'permisos.id')
                ->where('roles_permisos.rol', 'administrador')
                ->where('permisos.nombre', $permisoNombre)
                ->exists();
        }

        // Para otros roles, implementar lógica específica si es necesario
        return false;
    }

    // Método para obtener permisos del usuario
    public function obtenerPermisos()
    {
        if ($this->role === 'administrador') {
            return DB::table('roles_permisos')
                ->join('permisos', 'roles_permisos.permiso_id', '=', 'permisos.id')
                ->where('roles_permisos.rol', 'administrador')
                ->select('permisos.*')
                ->get();
        }

        return collect();
    }

    // Relaciones para funcionalidades de turista
    public function comentariosReservas()
    {
        return $this->hasMany(ComentariosReserva::class, 'id_usuario');
    }

    public function fotosViajes()
    {
        return $this->hasMany(FotosViaje::class, 'id_usuario');
    }

    public function likesFotos()
    {
        return $this->hasMany(LikesFoto::class, 'id_usuario');
    }

    public function reservas()
    {
        return $this->hasMany(Reservas::class, 'id_usuario');
    }

    // Método para verificar si el perfil es público
    public function esPerfilPublico()
    {
        return $this->privacidad_perfil === 'publico';
    }

    // Método para actualizar última actividad
    public function actualizarUltimaActividad()
    {
        $this->update(['ultima_actividad' => now()]);
    }
}
