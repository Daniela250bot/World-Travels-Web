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
        $guard = $this->determineGuard($request);
        $token = JWTAuth::getToken();

        if (!$token) {
            return response()->json(['error' => 'Token no proporcionado'], 401);
        }

        try {
            $usuario = JWTAuth::authenticate($token, false, $guard);

            if (!$usuario) {
                return response()->json(['error' => 'Token inválido o expirado'], 401);
            }

            // Asegurar que el usuario esté disponible en el guard
            Auth::guard($guard)->setUser($usuario);
        } catch (\Exception $e) {
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
     /**
      * Determinar qué guard usar basado en la ruta
      */
     private function determineGuard(Request $request): string
     {
         $path = strtolower($request->path());

         if (str_contains($path, 'empresas')) {
             return 'api-empresas';
         }

         if (str_contains($path, 'administradores')) {
             return 'api-administradores';
         }

         if (str_contains($path, 'usuarios')) {
             return 'api-usuarios';
         }

         return 'api';
     }


}