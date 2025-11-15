<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empresa - WORLD TRAVELS</title>
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
            <h2 class="text-4xl font-bold text-center mb-4 text-gray-800">Panel de Empresa</h2>
            <p class="text-center text-gray-600">Bienvenido, <span id="user-name" class="font-semibold"></span></p>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12" id="stats-section">
            <!-- Estadísticas se cargarán aquí -->
        </div>

        <!-- Panel de acciones de empresa -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-12">
            <h3 class="text-2xl font-bold mb-6 text-gray-800">Panel de Empresa</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <button onclick="createActivity()" class="bg-blue-600 text-white p-6 rounded-lg hover:bg-blue-700 transition text-center">
                    <h4 class="text-xl font-semibold mb-2">Crear Actividad</h4>
                    <p>Añade nuevas experiencias turísticas</p>
                </button>
                <button onclick="manageActivities()" class="bg-green-600 text-white p-6 rounded-lg hover:bg-green-700 transition text-center">
                    <h4 class="text-xl font-semibold mb-2">Gestionar Actividades</h4>
                    <p>Edita tus actividades existentes</p>
                </button>
                <button onclick="viewReservations()" class="bg-yellow-600 text-white p-6 rounded-lg hover:bg-yellow-700 transition text-center">
                    <h4 class="text-xl font-semibold mb-2">Ver Reservas</h4>
                    <p>Revisa solicitudes de participantes</p>
                </button>
            </div>
        </div>

        <!-- Lista de actividades de la empresa -->
        <div class="bg-white rounded-xl shadow-lg p-8" id="list-section">
            <!-- Lista se cargará aquí -->
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('token');
            if (!token) {
                window.location.href = '{{ route("login") }}';
                return;
            }

            loadUserData();
            loadStats();
            loadActivities();
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
                    document.getElementById('user-name').textContent = data.usuario.nombre;
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

            fetch('http://127.0.0.1:8000/api/listarActividades', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(data => {
                const totalActividades = data.length;

                statsSection.innerHTML = `
                    <div class="bg-blue-50 p-6 rounded-lg text-center">
                        <h3 class="text-2xl font-bold text-blue-600">${totalActividades}</h3>
                        <p class="text-gray-600">Actividades Creadas</p>
                    </div>
                    <div class="bg-green-50 p-6 rounded-lg text-center">
                        <h3 class="text-2xl font-bold text-green-600">0</h3>
                        <p class="text-gray-600">Reservas Recibidas</p>
                    </div>
                    <div class="bg-yellow-50 p-6 rounded-lg text-center">
                        <h3 class="text-2xl font-bold text-yellow-600">4.5</h3>
                        <p class="text-gray-600">Calificación Promedio</p>
                    </div>
                `;
            })
            .catch(error => console.error('Error cargando estadísticas:', error));
        }

        function loadActivities() {
            const listSection = document.getElementById('list-section');

            fetch('http://127.0.0.1:8000/api/listarActividades', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(data => {
                let html = '<h3 class="text-2xl font-bold mb-6 text-gray-800">Mis Actividades</h3>';
                if (data.length === 0) {
                    html += '<p class="text-gray-600">No has creado actividades aún.</p>';
                } else {
                    html += '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">';
                    data.forEach(actividad => {
                        html += `
                            <div class="border rounded-lg p-4">
                                <img src="${actividad.Imagen || 'https://via.placeholder.com/300x200?text=Actividad'}" alt="${actividad.Nombre_Actividad}" class="w-full h-32 object-cover rounded mb-2">
                                <h4 class="font-semibold">${actividad.Nombre_Actividad}</h4>
                                <p class="text-sm text-gray-600">${actividad.Descripcion}</p>
                                <p class="text-sm">Precio: $${actividad.Precio}</p>
                                <p class="text-sm">Cupo: ${actividad.Cupo_Maximo}</p>
                            </div>
                        `;
                    });
                    html += '</div>';
                }
                listSection.innerHTML = html;
            })
            .catch(error => console.error('Error cargando actividades:', error));
        }

        // Funciones de acción (placeholders)
        function createActivity() { alert('Función para crear actividad'); }
        function manageActivities() { alert('Función para gestionar actividades'); }
        function viewReservations() { alert('Función para ver reservas'); }
    </script>
</body>
</html>