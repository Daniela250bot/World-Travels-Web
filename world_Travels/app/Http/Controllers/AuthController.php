<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\Administrador;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Mail\CodigoVerificacionMail;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function registrar(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'Nombre' => 'required|string|max:255',
            'Apellido' => 'required|string|max:255',
            'Email' => 'required|string|email|max:255|unique:usuarios',
            'Contraseña' => 'required|string|min:8',
            'Telefono' => 'required|string|max:20',
            'Nacionalidad' => 'required|string|max:255',
            'Rol' => 'required|string|in:Turista,Guía Turístico,Administrador',
            'codigo_verificacion' => 'nullable|string|size:6'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Si es Guía Turístico, verificar el código de verificación
        if ($request->Rol === 'Guía Turístico') {
            if (!$request->has('codigo_verificacion') || empty($request->codigo_verificacion)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El código de verificación es requerido para guías turísticos',
                ], 422);
            }

            $usuarioExistente = Usuarios::where('Email', $request->Email)->first();
            if (!$usuarioExistente || !$usuarioExistente->codigo_verificacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debes solicitar un código de verificación primero',
                ], 422);
            }
            if ($request->codigo_verificacion !== $usuarioExistente->codigo_verificacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Código de verificación incorrecto',
                ], 422);
            }
        }

        $usuarios = Usuarios::create([
            'Nombre' => $request->Nombre,
            'Apellido' => $request->Apellido,
            'Email' => $request->Email,
            'Contraseña' => Hash::make($request->Contraseña),
            'Telefono' => $request->Telefono,
            'Nacionalidad' => $request->Nacionalidad,
            'Rol' => $request->Rol,
            'codigo_verificacion' => null, // Limpiar después del registro
        ]);

        try{
            $token = JWTAuth::fromUser($usuarios);
            return response()->json([
            'success' => true,
            'usuario' => $usuarios,
            'token' => $token,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo crear el Token JWT',
                'error' => $e->getMessage(),
            ], 500);

        }
    }

    public function login(Request $request)
    {
        Log::info('Login attempt', [
            'all_data' => $request->all(),
            'headers' => $request->headers->all(),
            'content_type' => $request->header('Content-Type'),
            'raw_content' => $request->getContent()
        ]);

        // Primero intentar con campos en minúscula (estándar)
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        // Si falla, probar con campos en mayúscula (legacy)
        if ($validator->fails()) {
            $legacyValidator = Validator::make($request->all(), [
                'Email' => 'required|string|email',
                'Contraseña' => 'required|string|min:8',
            ]);
            if (!$legacyValidator->fails()) {
                // Usar los campos en mayúscula
                $request->merge([
                    'email' => $request->Email,
                    'password' => $request->Contraseña,
                ]);
                $validator = $legacyValidator;
            }
        }
        if ($validator->fails()) {
            Log::warning('Login validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Buscar usuario en tabla users (centralizada)
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            try {
                $token = JWTAuth::fromUser($user);
                return response()->json([
                    'success' => true,
                    'token' => $token,
                    'usuario' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'verificado' => $user->verificado,
                    ],
                    'rol' => $user->role,
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creando token JWT',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Credenciales inválidas',
        ], 401);
    }

    public function webLogin(Request $request)
    {
        Log::info('Web login attempt', [
            'email' => $request->email,
            'has_password' => !empty($request->password),
            'all_data' => $request->all()
        ]);

        // Primero intentar con campos en minúscula (estándar)
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        // Si falla, probar con campos en mayúscula (legacy)
        if ($validator->fails()) {
            $legacyValidator = Validator::make($request->all(), [
                'Email' => 'required|string|email',
                'Contraseña' => 'required|string|min:8',
            ]);
            if (!$legacyValidator->fails()) {
                // Usar los campos en mayúscula
                $request->merge([
                    'email' => $request->Email,
                    'password' => $request->Contraseña,
                ]);
                $validator = $legacyValidator;
            }
        }

        if ($validator->fails()) {
            Log::warning('Web login validation failed', ['errors' => $validator->errors()]);
            return back()->withErrors($validator)->withInput();
        }

        // Buscar usuario en tabla users (centralizada)
        $user = User::where('email', $request->email)->first();

        Log::info('User lookup result', [
            'email' => $request->email,
            'user_found' => !is_null($user),
            'user_role' => $user ? $user->role : null,
            'user_verified' => $user ? $user->verificado : null
        ]);

        if ($user && Hash::check($request->password, $user->password)) {
            // Autenticar al usuario en la sesión web de Laravel
            Auth::login($user);

            // Generar token JWT para las llamadas API
            $token = JWTAuth::fromUser($user);

            Log::info('Web login successful', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $user->role
            ]);

            // Redirigir al dashboard con el token
            return redirect()->route('dashboard')->with('jwt_token', $token);
        }

        Log::warning('Web login failed: invalid credentials', [
            'email' => $request->email,
            'user_exists' => !is_null($user),
            'password_check' => $user ? Hash::check($request->password, $user->password) : false
        ]);
        return back()->withErrors(['email' => 'Credenciales inválidas'])->withInput();
    }

    public function logout(){
        try{
            $usuarios = JWTAuth::user(); // validar el usuario logeado
            JWTAuth::invalidate(JWTAuth::getToken()); // invalidar el token
            return response()->json([
                'success' => true,
                'message' => $usuarios->Nombre.' ha cerrado sesión correctamente',
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Error al cerrar la sesión',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function me()
    {
        try {
            $user = JWTAuth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Obtener datos adicionales según el rol desde la relación userable
            $additionalData = [];
            if ($user->userable) {
                $additionalData = $user->userable->toArray();
            }

            return response()->json([
                'success' => true,
                'usuario' => array_merge($user->toArray(), $additionalData),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener datos del usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function enviarCodigoVerificacion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Email' => 'required|string|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $codigo = $this->generarCodigoVerificacion($request->Email);

        try {
            Mail::to($request->Email)->send(new CodigoVerificacionMail($codigo));
            return response()->json([
                'success' => true,
                'message' => 'Código de verificación enviado al correo electrónico',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el código de verificación',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function generarCodigoVerificacion($email)
    {
        // Generar un código de 6 caracteres alfanuméricos
        $codigo = strtoupper(Str::random(6));

        // Guardar el código en la base de datos (temporalmente)
        // En producción, podrías usar cache o una tabla temporal
        Usuarios::updateOrCreate(
            ['Email' => $email],
            ['codigo_verificacion' => $codigo]
        );

        return $codigo;
    }

    public function sendResetCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró un usuario con ese correo electrónico',
            ], 404);
        }

        $codigo = $this->generarCodigoReset($request->email);

        try {
            Mail::to($request->email)->send(new ResetPasswordMail($codigo));
            return response()->json([
                'success' => true,
                'message' => 'Código de restablecimiento enviado al correo electrónico',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el código de restablecimiento',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'codigo' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado',
            ], 404);
        }

        // Verificar el código desde cache
        $cachedCode = Cache::get('reset_code_' . $request->email);
        if (!$cachedCode || $cachedCode !== $request->codigo) {
            return response()->json([
                'success' => false,
                'message' => 'Código de restablecimiento incorrecto',
            ], 422);
        }

        // Cambiar la contraseña
        $user->password = Hash::make($request->password);
        $user->save();

        // Limpiar el código del cache
        Cache::forget('reset_code_' . $request->email);

        return response()->json([
            'success' => true,
            'message' => 'Contraseña restablecida exitosamente',
        ], 200);
    }

    private function generarCodigoReset($email)
    {
        // Generar un código de 6 caracteres alfanuméricos
        $codigo = strtoupper(Str::random(6));

        // Guardar el código en cache por 24 horas
        Cache::put('reset_code_' . $email, $codigo, now()->addHours(24));

        return $codigo;
    }

    public function showForgotForm()
    {
        return view('forgot-password');
    }

    public function sendResetCodeWeb(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No se encontró un usuario con ese correo electrónico'])->withInput();
        }

        $codigo = $this->generarCodigoReset($request->email);

        try {
            Mail::to($request->email)->send(new ResetPasswordMail($codigo));
            return redirect()->route('password.reset', ['email' => $request->email])->with('status', 'Código de restablecimiento enviado al correo electrónico');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Error al enviar el código de restablecimiento'])->withInput();
        }
    }

    public function showResetForm(Request $request)
    {
        return view('reset-password', ['email' => $request->query('email')]);
    }

    public function resetPasswordWeb(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'codigo' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['codigo' => 'Usuario no encontrado'])->withInput();
        }

        // Verificar el código desde cache
        $cachedCode = Cache::get('reset_code_' . $request->email);
        if (!$cachedCode || $cachedCode !== $request->codigo) {
            return back()->withErrors(['codigo' => 'Código de restablecimiento incorrecto'])->withInput();
        }

        // Cambiar la contraseña
        $user->password = Hash::make($request->password);
        $user->save();

        // Limpiar el código del cache
        Cache::forget('reset_code_' . $request->email);

        return redirect()->route('login')->with('status', 'Contraseña restablecida exitosamente. Ahora puedes iniciar sesión.');
    }


}