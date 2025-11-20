<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        try {
            // Obtener el usuario autenticado via JWT
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['error' => 'No autenticado'], 401);
            }

            // Verificar si el usuario es administrador
            if ($user->role !== 'administrador') {
                return response()->json(['error' => 'Acceso denegado. Solo administradores pueden realizar esta acción'], 403);
            }

            // Verificar si el administrador tiene el permiso requerido
            $hasPermission = DB::table('roles_permisos')
                ->join('permisos', 'roles_permisos.permiso_id', '=', 'permisos.id')
                ->where('roles_permisos.rol', 'administrador')
                ->where('permisos.nombre', $permission)
                ->exists();

            if (!$hasPermission) {
                return response()->json(['error' => 'No tienes permisos para realizar esta acción'], 403);
            }

            return $next($request);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token inválido o expirado: ' . $e->getMessage()], 401);
        }
    }
}
