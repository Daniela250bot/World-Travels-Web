<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use App\Models\Empresa;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\CodigoVerificacionMail;

class RegistroController extends Controller
{
    /**
     * Registro unificado por roles
     */
    public function registrar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rol' => 'required|in:administrador,empresa,turista',
            'admin_name' => 'required_if:rol,administrador|string|max:255',
            'admin_apellido' => 'required_if:rol,administrador|string|max:255',
            'admin_telefono' => 'required_if:rol,administrador|string|max:20',
            'documento' => 'required_if:rol,administrador|string|max:20',
            'nombre' => 'required_if:rol,turista|string|max:255',
            'apellido' => 'required_if:rol,turista|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'required_if:rol,turista|string|max:20',
            'nacionalidad' => 'required_if:rol,turista|string|max:255',
            'numero' => 'required_if:rol,empresa|string|max:20',
            'direccion' => 'required_if:rol,empresa|string|max:255',
            'ciudad' => 'required_if:rol,empresa|string|max:255',
            'correo' => 'required_if:rol,empresa|string|email|max:255',
            'contraseña' => 'required|string|min:8',
            'password_confirmation' => 'required|string|min:8|same:contraseña',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $rol = $request->rol;

            switch ($rol) {
                case 'administrador':
                    return $this->registrarAdministrador($request);
                case 'empresa':
                    return $this->registrarEmpresa($request);
                case 'turista':
                    return $this->registrarTurista($request);
                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Rol no válido',
                    ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el registro',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Registro de administrador
     */
    private function registrarAdministrador(Request $request)
    {
        $data = [
            'nombre' => $request->admin_name,
            'apellido' => $request->admin_apellido,
            'correo_electronico' => $request->email,
            'telefono' => $request->admin_telefono,
            'documento' => $request->documento,
            'contraseña' => Hash::make($request->contraseña),
            'codigo_verificacion' => Administrador::generarCodigoVerificacion(),
        ];

        $administrador = Administrador::create($data);

        return response()->json([
            'success' => true,
            'data' => $administrador,
            'codigo_verificacion' => $data['codigo_verificacion'], // Solo para desarrollo
            'message' => 'Administrador registrado exitosamente. Código de verificación generado.',
        ], 201);
    }

    /**
     * Registro de empresa
     */
    private function registrarEmpresa(Request $request)
    {
        $data = [
            'numero' => $request->numero,
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
            'correo' => $request->correo,
            'contraseña' => Hash::make($request->contraseña),
            'codigo_verificacion' => Empresa::generarCodigoVerificacion(),
        ];

        $empresa = Empresa::create($data);

        // Enviar código de verificación por email
        try {
            Mail::to($empresa->correo)->send(new CodigoVerificacionMail($data['codigo_verificacion']));
        } catch (\Exception $e) {
            // Log error but don't fail registration
            Log::error('Error enviando email de verificación: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'data' => $empresa,
            'message' => 'Empresa registrada exitosamente. Código de verificación enviado por email.',
        ], 201);
    }

    /**
     * Registro de turista
     */
    private function registrarTurista(Request $request)
    {
        $data = [
            'Nombre' => $request->nombre,
            'Apellido' => $request->apellido,
            'Email' => $request->email,
            'Contraseña' => Hash::make($request->contraseña),
            'Telefono' => $request->telefono,
            'Nacionalidad' => $request->nacionalidad,
            'Fecha_Registro' => now(),
            'Rol' => 'Turista',
        ];

        $turista = Usuarios::create($data);

        return response()->json([
            'success' => true,
            'data' => $turista,
            'message' => 'Turista registrado exitosamente.',
        ], 201);
    }

    /**
     * Verificar código de administrador
     */
    public function verificarAdministrador(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'codigo_verificacion' => 'required|string|size:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $admin = Administrador::where('codigo_verificacion', $request->codigo_verificacion)->first();

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Código de verificación inválido',
            ], 400);
        }

        // Aquí podrías marcar como verificado o simplemente confirmar
        return response()->json([
            'success' => true,
            'message' => 'Código de verificación válido',
            'administrador' => $admin,
        ], 200);
    }

    /**
     * Verificar código de empresa
     */
    public function verificarEmpresa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'codigo_verificacion' => 'required|string|size:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $empresa = Empresa::where('codigo_verificacion', $request->codigo_verificacion)->first();

        if (!$empresa) {
            return response()->json([
                'success' => false,
                'message' => 'Código de verificación inválido',
            ], 400);
        }

        // Aquí podrías marcar como verificado
        return response()->json([
            'success' => true,
            'message' => 'Código de verificación válido',
            'empresa' => $empresa,
        ], 200);
    }
}
