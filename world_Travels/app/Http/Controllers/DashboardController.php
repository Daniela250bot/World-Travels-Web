<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        Log::info('Dashboard access attempt', [
            'user' => $user ? $user->toArray() : null,
            'session_id' => session()->getId(),
            'has_user' => !is_null($user),
            'auth_check' => Auth::check(),
            'session_data' => session()->all(),
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

        // Priorizar token de sesión flash, luego generar uno nuevo
        $jwtToken = session('jwt_token');
        if (!$jwtToken) {
            try {
                $jwtToken = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);
                Log::info('Generated new JWT token for dashboard', ['user_id' => $user->id]);
            } catch (\Exception $e) {
                Log::error('Error generating JWT token', ['error' => $e->getMessage()]);
                $jwtToken = null;
            }
        } else {
            Log::info('Using existing JWT token from session', ['user_id' => $user->id]);
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
    }
}
