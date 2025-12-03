<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Turismo en Boyacá</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            body { font-family: 'Instrument Sans', sans-serif; }
            .bg-blue-600 { background-color: #2563eb; }
            .text-white { color: white; }
            .p-4 { padding: 1rem; }
            .mb-4 { margin-bottom: 1rem; }
            .max-w-md { max-width: 28rem; }
            .mx-auto { margin-left: auto; margin-right: auto; }
            .rounded { border-radius: 0.25rem; }
            .shadow { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); }
            .bg-white { background-color: white; }
            .p-6 { padding: 1.5rem; }
            .text-center { text-align: center; }
            .text-2xl { font-size: 1.5rem; }
            .font-bold { font-weight: 700; }
            .mb-6 { margin-bottom: 1.5rem; }
            .block { display: block; }
            .w-full { width: 100%; }
            .border { border: 1px solid #d1d5db; }
            .rounded-md { border-radius: 0.375rem; }
            .px-3 { padding-left: 0.75rem; padding-right: 0.75rem; }
            .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
            .mb-2 { margin-bottom: 0.5rem; }
            .bg-blue-500 { background-color: #3b82f6; }
            .hover\:bg-blue-700:hover { background-color: #1d4ed8; }
            .text-blue-600 { color: #2563eb; }
            .mt-4 { margin-top: 1rem; }
        </style>
    @endif
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center bg-gray-50">
        <div class="bg-white p-6 rounded-lg shadow-md max-w-md w-full">
            <div class="mb-4">
                <a href="{{ route('home') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Volver al Inicio
                </a>
            </div>
            <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Iniciar Sesión</h2>
            <form id="login-form" method="POST" action="{{ route('web.login') }}">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block mb-2">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="w-full px-3 py-2 border rounded-md" autocomplete="email" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block mb-2">Contraseña</label>
                    <input type="password" id="password" name="password" class="w-full px-3 py-2 border rounded-md" autocomplete="current-password" required>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Iniciar Sesión</button>
            </form>
            <p class="text-center mt-4">
                <a href="{{ route('password.request') }}" class="text-blue-600">¿Olvidaste tu contraseña?</a>
            </p>
            <p class="text-center mt-2">¿No tienes cuenta? <a href="{{ route('register') }}" class="text-blue-600">Regístrate</a></p>

            @if ($errors->any())
                <div class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

</body>
</html>