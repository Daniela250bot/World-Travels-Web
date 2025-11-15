<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use App\Models\Empresa;
use App\Models\Usuarios;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Mail\CodigoVerificacionMail;

class RegistroController extends Controller
{
    /**
     * Registro unificado por roles
     */
    public function registrar(Request $request)
    {
        // Usar request->all() directamente para obtener los datos
        $data = $request->all();

        Log::info('Datos recibidos con request->all():', $data);

        if (!$data || !is_array($data) || empty($data)) {
            Log::error('Error: No se pudieron obtener los datos del request');
            return response()->json([
                'success' => false,
                'message' => 'Error en el formato de los datos',
            ], 400);
        }

        $validator = Validator::make($data, [
            'rol' => 'required|in:administrador,empresa,turista',
            'admin_name' => 'required_if:rol,administrador|string|max:255',
            'admin_apellido' => 'required_if:rol,administrador|string|max:255',
            'admin_telefono' => 'required_if:rol,administrador|string|max:20',
            'documento' => 'required_if:rol,administrador|string|max:20',
            'email' => 'required_if:rol,administrador,email|required_if:rol,turista,email|email|max:255',
            'telefono' => 'required_if:rol,turista|string|max:20',
            'nacionalidad' => 'required_if:rol,turista|string|max:255',
            'numero' => 'required_if:rol,empresa|string|max:20',
            'direccion' => 'required_if:rol,empresa|string|max:255',
            'ciudad' => 'required_if:rol,empresa|string|max:255',
            'correo' => 'required_if:rol,empresa|string|email|max:255',
            'contraseña' => 'required|string|min:8',
            'password_confirmation' => 'required|string|min:8|same:contraseña',
        ]);

        // Debug: mostrar qué campos están llegando
        Log::info('Campos validados en registro:', $data);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $rol = $data['rol'];

            switch ($rol) {
                case 'administrador':
                    return $this->registrarAdministrador($data);
                case 'empresa':
                    return $this->registrarEmpresa($data);
                case 'turista':
                    return $this->registrarTurista($data);
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
     * Parsear un string con formato de objeto JavaScript
     */
    private function parseJavaScriptObject($jsString)
    {
        Log::info('Parseando string JS:', ['string' => $jsString]);

        // Remover llaves externas
        $jsString = trim($jsString, '{}');

        $result = [];
        $pairs = explode(',', $jsString);

        foreach ($pairs as $pair) {
            $pair = trim($pair);
            if (empty($pair)) continue;

            // Separar clave:valor
            $colonPos = strpos($pair, ':');
            if ($colonPos === false) continue;

            $key = trim(substr($pair, 0, $colonPos));
            $value = trim(substr($pair, $colonPos + 1));

            // Remover comillas de la clave si existen
            $key = trim($key, "'\"");

            // Remover comillas del valor si existen
            $value = trim($value, "'\"");

            $result[$key] = $value;
        }

        Log::info('Resultado del parseo:', ['result' => $result]);
        return $result;
    }

    /**
     * Registro de administrador
     */
    private function registrarAdministrador($data)
    {
        return DB::transaction(function () use ($data) {
            // Generar código de verificación primero
            $codigoVerificacion = Administrador::generarCodigoVerificacion();

            // Crear registro específico en administradores PRIMERO
            $administrador = Administrador::create([
                'nombre' => $data['admin_name'],
                'apellido' => $data['admin_apellido'],
                'correo_electronico' => $data['email'],
                'telefono' => $data['admin_telefono'],
                'documento' => $data['documento'],
                'contraseña' => Hash::make($data['contraseña']),
                'codigo_verificacion' => $codigoVerificacion,
            ]);

            // Crear usuario en tabla users DESPUÉS
            $user = User::create([
                'name' => $data['admin_name'] . ' ' . $data['admin_apellido'],
                'email' => $data['email'],
                'password' => Hash::make($data['contraseña']),
                'role' => 'administrador',
                'userable_type' => Administrador::class,
                'userable_id' => $administrador->id, // Ahora sí existe
                'codigo_verificacion' => $codigoVerificacion,
                'verificado' => false,
            ]);

            // Actualizar user_id en administrador
            $administrador->update(['user_id' => $user->id]);

            return response()->json([
                'success' => true,
                'data' => $administrador,
                'user' => $user,
                'codigo_verificacion' => $codigoVerificacion, // Solo para desarrollo
                'message' => 'Administrador registrado exitosamente. Código de verificación generado.',
            ], 201);
        });
    }

    /**
     * Registro de empresa
     */
    private function registrarEmpresa($data)
    {
        return DB::transaction(function () use ($data) {
            // Generar código de verificación primero
            $codigoVerificacion = Empresa::generarCodigoVerificacion();

            // Crear registro específico en empresas PRIMERO
            $empresa = Empresa::create([
                'numero' => $data['numero'],
                'nombre' => $data['nombre'],
                'direccion' => $data['direccion'],
                'ciudad' => $data['ciudad'],
                'correo' => $data['correo'],
                'contraseña' => Hash::make($data['contraseña']),
                'codigo_verificacion' => $codigoVerificacion,
            ]);

            // Crear usuario en tabla users DESPUÉS
            $user = User::create([
                'name' => $data['nombre'],
                'email' => $data['correo'],
                'password' => Hash::make($data['contraseña']),
                'role' => 'empresa',
                'userable_type' => Empresa::class,
                'userable_id' => $empresa->id, // Ahora sí existe
                'codigo_verificacion' => $codigoVerificacion,
                'verificado' => false,
            ]);

            // Actualizar user_id en empresa
            $empresa->update(['user_id' => $user->id]);

            // Enviar código de verificación por email
            try {
                Mail::to($empresa->correo)->send(new CodigoVerificacionMail($codigoVerificacion));
            } catch (\Exception $e) {
                // Log error but don't fail registration
                Log::error('Error enviando email de verificación: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'data' => $empresa,
                'user' => $user,
                'codigo_verificacion' => $codigoVerificacion, // Solo para desarrollo
                'message' => 'Empresa registrada exitosamente. Código de verificación enviado por email.',
            ], 201);
        });
    }

    /**
     * Registro de turista
     */
    private function registrarTurista($data)
    {
        return DB::transaction(function () use ($data) {
            // Crear registro específico en usuarios PRIMERO
            $turista = Usuarios::create([
                'Nombre' => $data['nombre'],
                'Apellido' => $data['apellido'],
                'Email' => $data['email'],
                'Contraseña' => Hash::make($data['contraseña']),
                'Telefono' => $data['telefono'],
                'Nacionalidad' => $data['nacionalidad'],
                'Fecha_Registro' => now(),
                'Rol' => 'Turista',
            ]);

            // Crear usuario en tabla users DESPUÉS
            $user = User::create([
                'name' => $data['nombre'] . ' ' . $data['apellido'],
                'email' => $data['email'],
                'password' => Hash::make($data['contraseña']),
                'role' => 'turista',
                'userable_type' => Usuarios::class,
                'userable_id' => $turista->id, // Ahora sí existe
                'verificado' => true, // Turistas no requieren verificación
            ]);

            // Actualizar user_id en turista
            $turista->update(['user_id' => $user->id]);

            return response()->json([
                'success' => true,
                'data' => $turista,
                'user' => $user,
                'message' => 'Turista registrado exitosamente.',
            ], 201);
        });
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
