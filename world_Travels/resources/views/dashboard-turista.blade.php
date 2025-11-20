<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Turista - WORLD TRAVELS</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-blue-600">WORLD TRAVELS</h1>
            <nav class="space-x-6">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 transition">Inicio</a>
                <a href="{{ route('search') }}" class="text-gray-700 hover:text-blue-600 transition">Buscar Actividades</a>
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 transition">Mi Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="text-gray-700 hover:text-blue-600 transition bg-transparent border-none cursor-pointer">Cerrar Sesión</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-12">
        <div class="mb-8">
            <h2 class="text-4xl font-bold text-center mb-4 text-gray-800">Mi Dashboard</h2>
            <p class="text-center text-gray-600">Bienvenido, <span id="user-name" class="font-semibold"></span></p>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12" id="stats-section">
            <!-- Estadísticas se cargarán aquí -->
        </div>

        <!-- Panel de acciones de turista -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-12">
            <h3 class="text-2xl font-bold mb-6 text-gray-800">Mis Acciones</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="{{ route('search') }}" class="bg-blue-600 text-white p-6 rounded-lg hover:bg-blue-700 transition text-center">
                    <h4 class="text-xl font-semibold mb-2">Buscar Actividades</h4>
                    <p>Explora nuevas aventuras en Boyacá</p>
                </a>
                <button onclick="showReservations()" class="bg-green-600 text-white p-6 rounded-lg hover:bg-green-700 transition text-center">
                    <h4 class="text-xl font-semibold mb-2">Mis Reservas</h4>
                    <p>Gestiona tus reservas activas</p>
                </button>
            </div>
        </div>

        <!-- Lista de reservas del turista -->
        <div class="bg-white rounded-xl shadow-lg p-8" id="list-section">
            <!-- Lista se cargará aquí -->
        </div>
    </main>

    <script>
        // Guardar el token JWT en localStorage después del login
        @if(session('jwt_token'))
            localStorage.setItem('token', '{{ session("jwt_token") }}');
            localStorage.setItem('user_role', 'turista');
        @endif

        // Función auxiliar para hacer fetch con manejo automático de errores 401
        function fetchWithAuth(url, options = {}) {
            const token = localStorage.getItem('token');
            if (!token) {
                alert('Tu sesión ha expirado. Por favor, inicia sesión nuevamente.');
                window.location.href = '{{ route("login") }}';
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
                        window.location.href = '{{ route("login") }}';
                        throw new Error('Unauthorized');
                    }
                    return response;
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('token');
            if (!token) {
                window.location.href = '{{ route("login") }}';
                return;
            }

            loadUserData();
            loadStats();
            loadReservations();
        });

        function loadUserData() {
            fetch('http://127.0.0.1:8000/api/me', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('user-name').textContent = data.usuario.Nombre;
                }
            })
            .catch(error => {
                console.error('Error cargando datos del usuario:', error);
                localStorage.removeItem('token');
                window.location.href = '{{ route("login") }}';
            });
        }

        function loadStats() {
            const statsSection = document.getElementById('stats-section');

            fetch('http://127.0.0.1:8000/api/listarReservas', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(data => {
                const totalReservas = data.length;
                const reservasConfirmadas = data.filter(r => r.Estado === 'confirmada').length;

                statsSection.innerHTML = `
                    <div class="bg-blue-50 p-6 rounded-lg text-center">
                        <h3 class="text-2xl font-bold text-blue-600">${totalReservas}</h3>
                        <p class="text-gray-600">Total Reservas</p>
                    </div>
                    <div class="bg-green-50 p-6 rounded-lg text-center">
                        <h3 class="text-2xl font-bold text-green-600">${reservasConfirmadas}</h3>
                        <p class="text-gray-600">Reservas Confirmadas</p>
                    </div>
                    <div class="bg-yellow-50 p-6 rounded-lg text-center">
                        <h3 class="text-2xl font-bold text-yellow-600">${totalReservas - reservasConfirmadas}</h3>
                        <p class="text-gray-600">Reservas Pendientes</p>
                    </div>
                `;
            })
            .catch(error => console.error('Error cargando estadísticas:', error));
        }

        function loadReservations() {
            const listSection = document.getElementById('list-section');

            fetch('http://127.0.0.1:8000/api/listarReservas', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(data => {
                let html = '<h3 class="text-2xl font-bold mb-6 text-gray-800">Mis Reservas</h3>';
                if (data.length === 0) {
                    html += '<p class="text-gray-600">No tienes reservas activas.</p>';
                } else {
                    html += '<div class="space-y-4">';
                    data.forEach(reserva => {
                        html += `
                            <div class="border rounded-lg p-4">
                                <h4 class="font-semibold">Reserva #${reserva.id}</h4>
                                <p>Fecha: ${reserva.Fecha_Reserva}</p>
                                <p>Personas: ${reserva.Numero_Personas}</p>
                                <p>Estado: <span class="px-2 py-1 rounded text-sm ${reserva.Estado === 'confirmada' ? 'bg-green-100 text-green-800' : reserva.Estado === 'cancelada' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'}">${reserva.Estado}</span></p>
                            </div>
                        `;
                    });
                    html += '</div>';
                }
                listSection.innerHTML = html;
            })
            .catch(error => console.error('Error cargando reservas:', error));
        }

        // Funciones de acción
        function showReservations() {
            // Ya estamos en la sección de reservas, quizás hacer scroll
            document.getElementById('list-section').scrollIntoView({ behavior: 'smooth' });
        }
    </script>
</body>
</html>