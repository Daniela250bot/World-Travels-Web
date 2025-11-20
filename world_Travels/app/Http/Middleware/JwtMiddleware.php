<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles = null): Response
    {
        try{
            // Autenticar usando JWT
            $usuario = JWTAuth::parseToken()->authenticate();

            if (!$usuario) {
                return response()->json(['error' => 'Token inválido o expirado'], 401);
            }
        }catch(\Exception $e){
            // si no hay token valido o esta vencido, devolvemos error
            return response()->json(['error' => 'Token inválido o expirado'], 401);
        }

        // Verificar roles si se especifica
        if ($roles) {
            $userRole = $usuario->role ?? $usuario->Rol ?? null;
            if (!$userRole || $userRole !== $roles) {
                return response()->json(['error' => 'Acceso denegado. No tienes el rol necesario'], 403);
            }
        }

        return $next($request); //  para que continue con la peticion
    }

    /**
     * Determinar qué guard usar basado en la ruta
     */
    private function determineGuard(Request $request): string
    {
        $path = $request->path();

        // Si la ruta contiene 'empresas', usar guard de empresas
        if (str_contains($path, 'empresas/')) {
            return 'api-empresas';
        }

        // Si la ruta contiene 'administradores', usar guard de administradores
        if (str_contains($path, 'administradores/')) {
            return 'api-administradores';
        }

        // Si la ruta contiene usuarios específicos, usar guard de usuarios
        if (str_contains($path, 'usuarios/')) {
            return 'api-usuarios';
        }

        // Por defecto, usar guard api
        return 'api';
    }

}