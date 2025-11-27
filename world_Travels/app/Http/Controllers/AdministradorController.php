<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdministradorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Verificar permiso para ver administradores
            $admin = JWTAuth::user();
            if (!$admin->tienePermiso('ver_administradores')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para ver administradores',
                ], 403);
            }

            $administradores = Administrador::all();
            return response()->json([
                'success' => true,
                'data' => $administradores,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener administradores',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verificar permiso para crear administradores
        $admin = JWTAuth::user();
        if (!$admin->tienePermiso('crear_administradores')) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para crear administradores',
            ], 403);
        }

        $validator = Validator::make($request->all(), Administrador::rules());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $data = $request->all();
            $data['contraseña'] = Hash::make($request->contraseña);

            $administrador = Administrador::create($data);

            return response()->json([
                'success' => true,
                'data' => $administrador,
                'message' => 'Administrador creado exitosamente',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear administrador',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $administrador = Administrador::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $administrador,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Administrador no encontrado',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Verificar permiso para editar administradores
        $admin = JWTAuth::user();
        if (!$admin->tienePermiso('editar_administradores')) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para editar administradores',
            ], 403);
        }

        try {
            $administrador = Administrador::findOrFail($id);

            $validator = Validator::make($request->all(), Administrador::rules($id));

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $data = $request->all();
            if ($request->has('contraseña') && !empty($request->contraseña)) {
                $data['contraseña'] = Hash::make($request->contraseña);
            } else {
                unset($data['contraseña']);
            }

            $administrador->update($data);

            return response()->json([
                'success' => true,
                'data' => $administrador,
                'message' => 'Administrador actualizado exitosamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar administrador',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Verificar permiso para eliminar administradores
        $admin = JWTAuth::user();
        if (!$admin->tienePermiso('eliminar_administradores')) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para eliminar administradores',
            ], 403);
        }

        try {
            $administrador = Administrador::findOrFail($id);
            $administrador->delete();

            return response()->json([
                'success' => true,
                'message' => 'Administrador eliminado exitosamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar administrador',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Login para administradores
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'correo_electronico' => 'required|string|email',
            'contraseña' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $credentials = $request->only('correo_electronico', 'contraseña');

        try {
            if (!$token = JWTAuth::attempt($credentials, ['provider' => 'administradores'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales inválidas',
                ], 401);
            }

            return response()->json([
                'success' => true,
                'token' => $token,
                'administrador' => JWTAuth::user(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la autenticación',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Logout para administradores
     */
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'success' => true,
                'message' => 'Sesión cerrada exitosamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cerrar sesión',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtener información del administrador autenticado
     */
    public function me()
    {
        try {
            $admin = JWTAuth::user();
            $user = $admin->user; // Relación con User

            $adminData = $admin->toArray();
            $adminData['foto_perfil'] = $user ? $user->foto_perfil : null;

            return response()->json([
                'success' => true,
                'administrador' => $adminData,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener información del administrador',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Actualizar perfil del administrador autenticado
     */
    public function updateProfile(Request $request)
    {
        try {
            $admin = JWTAuth::user();
            $user = $admin->user;

            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:255',
                'correo_electronico' => 'required|string|email|max:255|unique:administradores,correo_electronico,' . $admin->id,
                'telefono' => 'nullable|string|max:20',
                'contraseña' => 'nullable|string|min:8|confirmed',
                'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $data = $request->only(['nombre', 'correo_electronico', 'telefono']);

            if ($request->has('contraseña') && !empty($request->contraseña)) {
                $data['contraseña'] = Hash::make($request->contraseña);
            }

            $admin->update($data);

            // Manejar subida de foto de perfil
            if ($request->hasFile('foto_perfil')) {
                // Eliminar foto anterior si existe
                if ($user && $user->foto_perfil) {
                    $oldPath = storage_path('app/public/' . $user->foto_perfil);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                // Subir nueva foto
                $file = $request->file('foto_perfil');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('fotos_perfil', $filename, 'public');

                if ($user) {
                    $user->update(['foto_perfil' => $path]);
                }
            }

            // Recargar admin con foto actualizada
            $adminData = $admin->fresh();
            $adminData['foto_perfil'] = $user ? $user->foto_perfil : null;

            return response()->json([
                'success' => true,
                'message' => 'Perfil actualizado exitosamente',
                'administrador' => $adminData,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar perfil',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Eliminar foto de perfil del administrador autenticado
     */
    public function deletePhoto()
    {
        try {
            $admin = JWTAuth::user();
            $user = $admin->user;

            if ($user && $user->foto_perfil) {
                // Eliminar archivo físico
                $filePath = storage_path('app/public/' . $user->foto_perfil);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                // Eliminar referencia en base de datos
                $user->update(['foto_perfil' => null]);

                return response()->json([
                    'success' => true,
                    'message' => 'Foto de perfil eliminada exitosamente',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay foto de perfil para eliminar',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar foto de perfil',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Eliminar cuenta del administrador autenticado
     */
    public function deleteProfile()
    {
        try {
            $admin = JWTAuth::user();

            // Verificar que no sea el único administrador
            $totalAdmins = Administrador::count();
            if ($totalAdmins <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar la cuenta. Debe haber al menos un administrador en el sistema.',
                ], 422);
            }

            $admin->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cuenta eliminada exitosamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar cuenta',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
