<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'WORLD TRAVELS' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Leaflet Control Geocoder CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.css" />

    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Scripts adicionales -->
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-blue-600">WORLD TRAVELS</h1>
            <nav class="space-x-6">
                <a href="{{ route('search') }}" class="text-gray-700 hover:text-blue-600 transition">Buscar Actividades</a>
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 transition">Inicio</a>
                @yield('nav-links')
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="text-gray-700 hover:text-blue-600 transition bg-transparent border-none cursor-pointer">Cerrar Sesión</button>
                </form>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    @yield('hero')

    <!-- Main Content -->
    <main class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50">
        <div class="max-w-6xl mx-auto px-8 py-16 -mt-16 relative z-10">
            @yield('content')
        </div>
    </main>

    <!-- Modals -->
    @yield('modals')

    <!-- Scripts -->
    <script>
        // Guardar el token JWT
        localStorage.setItem('token', '{{ session("jwt_token", "") }}');
        localStorage.setItem('user_role', '{{ auth()->user()->role ?? "guest" }}');

        // Función auxiliar para hacer fetch con manejo automático de errores 401
        function fetchWithAuth(url, options = {}) {
            const token = localStorage.getItem('token');
            if (!token) {
                alert('Tu sesión ha expirado. Por favor, inicia sesión nuevamente.');
                window.location.href = '/login';
                return Promise.reject(new Error('No token found'));
            }

            const headers = {
                'Authorization': 'Bearer ' + token,
                ...options.headers
            };

            return fetch(url, { ...options, headers })
                .then(response => {
                    if (response.status === 401) {
                        alert('Tu sesión ha expirado. Por favor, inicia sesión nuevamente.');
                        localStorage.removeItem('token');
                        window.location.href = '/login';
                        throw new Error('Unauthorized');
                    }
                    return response;
                });
        }

        // Función para mostrar notificaciones
        function showNotification(message, type = 'info') {
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                info: 'bg-blue-500',
                warning: 'bg-yellow-500'
            };

            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-4 rounded-lg shadow-lg z-50 max-w-sm`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(notification);

            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 5000);
        }

        // Función para mostrar loading
        function showLoading(element, message = 'Cargando...') {
            element.innerHTML = `<p class="col-span-full text-center text-gray-500 text-lg">${message}</p>`;
        }

        // Función para mostrar error
        function showError(element, message = 'Error al cargar') {
            element.innerHTML = `<p class="col-span-full text-center text-red-500 text-lg">${message}</p>`;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('token');
            if (!token && window.location.pathname !== '/login') {
                window.location.href = '/login';
                return;
            }

            // Inicializar funcionalidades específicas
            {{ $initScripts ?? '' }}
        });
    </script>

    <!-- Scripts de inicialización específicos -->
    @yield('page-scripts')

    <!-- Scripts específicos de cada página -->
    @stack('scripts')

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Leaflet Control Geocoder JS -->
    <script src="https://unpkg.com/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.js"></script>

    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

    <!-- Chart.js -->
    <script src='https://cdn.jsdelivr.net/npm/chart.js'></script>
</body>
</html>