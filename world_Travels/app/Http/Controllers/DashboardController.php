<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();

            Log::info('Dashboard access attempt', [
                'user_id' => $user ? $user->id : null,
                'user_email' => $user ? $user->email : null,
                'session_id' => session()->getId(),
                'has_user' => !is_null($user),
                'auth_check' => Auth::check(),
                'request_headers' => request()->headers->all()
            ]);

            if (!$user) {
                Log::warning('No authenticated user found, redirecting to login', [
                    'auth_check' => Auth::check(),
                    'session_id' => session()->getId()
                ]);
                return redirect()->route('login');
            }

            Log::info('User role: ' . $user->role, [
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);

            // Generar token JWT para las llamadas API
            try {
                $jwtToken = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
                session()->put('jwt_token', $jwtToken);
                Log::info('Generated new JWT token for dashboard', ['user_id' => $user->id]);
            } catch (\Exception $e) {
                Log::error('Error generating JWT token', ['error' => $e->getMessage()]);
                $jwtToken = null;
            }

            switch ($user->role) {
                case 'administrador':
                    Log::info('Redirecting to dashboard-administrador');
                    return view('dashboard-administrador', compact('jwtToken'));
                case 'empresa':
                    Log::info('Redirecting to dashboard-empresa');
                    return view('dashboard-empresa', compact('jwtToken'));
                case 'turista':
                    Log::info('Redirecting to dashboard-turista');
                    return view('dashboard-turista', compact('jwtToken'));
                default:
                    Log::warning('Unknown role: ' . $user->role . ', redirecting to home');
                    return redirect()->route('home');
            }
        } catch (\Exception $e) {
            Log::error('Error in DashboardController::index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
