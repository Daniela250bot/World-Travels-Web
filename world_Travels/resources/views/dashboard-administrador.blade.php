<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador - WORLD TRAVELS</title>
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
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 transition">Administrador</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="text-gray-700 hover:text-blue-600 transition bg-transparent border-none cursor-pointer">Cerrar Sesión</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-12">
        <div class="mb-8">
            <h2 class="text-4xl font-bold text-center mb-4 text-gray-800">Panel Administrativo</h2>
            <p class="text-center text-gray-600">Bienvenido, Administrador <span id="user-name" class="font-semibold"></span></p>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12" id="stats-section">
            <!-- Estadísticas se cargarán aquí -->
        </div>

        <!-- Panel de acciones administrativas -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-12">
            <h3 class="text-2xl font-bold mb-6 text-gray-800">Panel Administrativo</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <button onclick="manageUsers()" class="bg-blue-600 text-white p-6 rounded-lg hover:bg-blue-700 transition text-center">
                    <h4 class="text-xl font-semibold mb-2">Gestionar Usuarios</h4>
                    <p>Administra cuentas de usuario</p>
                </button>
                <button onclick="manageActivities()" class="bg-green-600 text-white p-6 rounded-lg hover:bg-green-700 transition text-center">
                    <h4 class="text-xl font-semibold mb-2">Gestionar Actividades</h4>
                    <p>Supervisa todas las actividades</p>
                </button>
                <button onclick="manageCategories()" class="bg-yellow-600 text-white p-6 rounded-lg hover:bg-yellow-700 transition text-center">
                    <h4 class="text-xl font-semibold mb-2">Categorías</h4>
                    <p>Gestiona categorías de actividades</p>
                </button>
                <button onclick="viewReports()" class="bg-purple-600 text-white p-6 rounded-lg hover:bg-purple-700 transition text-center">
                    <h4 class="text-xl font-semibold mb-2">Reportes</h4>
                    <p>Visualiza estadísticas del sistema</p>
                </button>
                <button onclick="manageCategories()" class="bg-yellow-600 text-white p-6 rounded-lg hover:bg-yellow-700 transition text-center">
                    <h4 class="text-xl font-semibold mb-2">Municipios</h4>
                    <p>Gestiona municipios</p>
                </button>
                <button onclick="manageCategories()" class="bg-yellow-600 text-white p-6 rounded-lg hover:bg-yellow-700 transition text-center">
                    <h4 class="text-xl font-semibold mb-2">Empresas</h4>
                    <p>Gestiona categorías de empresas</p>
                </button>
                
            </div>
        </div>

        <!-- Lista de gestión de usuarios -->
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
            loadUserList();
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

            Promise.all([
                fetch('http://127.0.0.1:8000/api/listarUsuarios').then(r => r.json()),
                fetch('http://127.0.0.1:8000/api/listarActividades').then(r => r.json()),
                fetch('http://127.0.0.1:8000/api/listarReservas').then(r => r.json())
            ])
            .then(([usuarios, actividades, reservas]) => {
                statsSection.innerHTML = `
                    <div class="bg-blue-50 p-6 rounded-lg text-center">
                        <h3 class="text-2xl font-bold text-blue-600">${usuarios.length}</h3>
                        <p class="text-gray-600">Total Usuarios</p>
                    </div>
                    <div class="bg-green-50 p-6 rounded-lg text-center">
                        <h3 class="text-2xl font-bold text-green-600">${actividades.length}</h3>
                        <p class="text-gray-600">Total Actividades</p>
                    </div>
                    <div class="bg-yellow-50 p-6 rounded-lg text-center">
                        <h3 class="text-2xl font-bold text-yellow-600">${reservas.length}</h3>
                        <p class="text-gray-600">Total Reservas</p>
                    </div>
                `;
            })
            .catch(error => console.error('Error cargando estadísticas:', error));
        }

        function loadUserList() {
            const listSection = document.getElementById('list-section');

            fetch('http://127.0.0.1:8000/api/listarUsuarios', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(data => {
                let html = '<h3 class="text-2xl font-bold mb-6 text-gray-800">Gestión de Usuarios</h3>';
                html += '<div class="overflow-x-auto">';
                html += '<table class="w-full table-auto">';
                html += '<thead><tr class="bg-gray-100"><th class="px-4 py-2">Nombre</th><th class="px-4 py-2">Email</th><th class="px-4 py-2">Rol</th><th class="px-4 py-2">Acciones</th></tr></thead>';
                html += '<tbody>';
                data.forEach(usuario => {
                    html += `
                        <tr class="border-b">
                            <td class="px-4 py-2">${usuario.Nombre} ${usuario.Apellido}</td>
                            <td class="px-4 py-2">${usuario.Email}</td>
                            <td class="px-4 py-2">${usuario.Rol}</td>
                            <td class="px-4 py-2">
                                <button onclick="editUser(${usuario.id})" class="bg-blue-500 text-white px-2 py-1 rounded text-sm mr-2">Editar</button>
                                <button onclick="deleteUser(${usuario.id})" class="bg-red-500 text-white px-2 py-1 rounded text-sm">Eliminar</button>
                            </td>
                        </tr>
                    `;
                });
                html += '</tbody></table></div>';
                listSection.innerHTML = html;
            })
            .catch(error => console.error('Error cargando lista de usuarios:', error));
        }

        // Funciones de acción (placeholders)
        function manageUsers() { alert('Función para gestionar usuarios'); }
        function manageActivities() { alert('Función para gestionar actividades'); }
        function manageCategories() { alert('Función para gestionar categorías'); }
        function viewReports() { alert('Función para ver reportes'); }
        function editUser(id) { alert('Editar usuario ' + id); }
        function deleteUser(id) { alert('Eliminar usuario ' + id); }
    </script>
</body>
</html>