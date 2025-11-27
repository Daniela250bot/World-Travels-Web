<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/search', function () {
    return view('search');
})->name('search');

Route::get('/reportes', [App\Http\Controllers\ReportesController::class, 'index'])->name('reportes');
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
Route::get('/perfil', function () {
    return view('perfil');
})->name('perfil');

// Login web (no API)
Route::post('/login', [App\Http\Controllers\AuthController::class, 'webLogin'])->name('web.login');

// Ruta temporal para verificar usuarios
Route::get('/check-users', function () {
    $users = \App\Models\User::all();
    $output = "<h1>Usuarios Registrados</h1><pre>";
    foreach ($users as $user) {
        $output .= "ID: {$user->id}\n";
        $output .= "Email: {$user->email}\n";
        $output .= "Role: " . ($user->role ?? 'NULL') . "\n";
        $output .= "Verificado: " . ($user->verificado ?? 'NULL') . "\n";
        $output .= "---\n";
    }
    $output .= "</pre>";
    return $output;
});

Route::post('/logout', function () {
    // Aquí podrías manejar logout si es necesario, pero como es API, quizás redirigir
    return redirect()->route('home');
})->name('logout');

// Ruta para dashboard de empresa
Route::get('/dashboard-empresa', function () {
    return view('dashboard-empresa');
})->name('dashboard-empresa');

// Ruta para dashboard de administrador
Route::get('/dashboard-administrador', function () {
    return view('dashboard-administrador');
})->name('dashboard-administrador');

// Ruta para perfil de administrador
Route::get('/perfil-administrador', function () {
    return view('perfil-administrador');
})->name('perfil-administrador');

// Rutas para reset de contraseña
Route::get('/forgot-password', [App\Http\Controllers\AuthController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\AuthController::class, 'sendResetCodeWeb'])->name('password.email');
Route::get('/reset-password', [App\Http\Controllers\AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\AuthController::class, 'resetPasswordWeb'])->name('password.update');
