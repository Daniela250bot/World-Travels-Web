<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - WORLD TRAVELS</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-blue-600">WORLD TRAVELS</h1>
            <nav class="flex items-center space-x-6">
                @auth
                    <div id="reservations-counter" class="hidden bg-white text-blue-600 px-3 py-1 rounded-full text-sm font-semibold mr-4 border border-blue-200">
                        <span id="active-reservations-count">0</span> reservas activas
                    </div>
                @endauth
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 transition">Mi Dashboard</a>
                <a href="{{ route('search') }}" class="text-gray-700 hover:text-blue-600 transition">Buscar Actividades</a>
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

        <!-- Panel de acciones según rol -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-12" id="actions-section">
            <!-- Acciones se cargarán aquí -->
        </div>

        <!-- Lista de actividades/reservas -->
        <div class="bg-white rounded-xl shadow-lg p-8" id="list-section">
            <!-- Lista se cargará aquí -->
        </div>
    </main>

    <script>

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

        // Función para cargar el contador de reservas
        function loadReservationsCounter() {
            const token = localStorage.getItem('token');
            if (!token) return;

            fetchWithAuth('http://127.0.0.1:8000/api/turista/reservas')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const activeCount = data.reservas.proximas.length;
                    updateReservationsCounter(activeCount);
                }
            })
            .catch(error => console.error('Error cargando contador de reservas:', error));
        }

        // Función para actualizar el contador visual
        function updateReservationsCounter(count) {
            const counterElement = document.getElementById('reservations-counter');
            const countElement = document.getElementById('active-reservations-count');

            if (counterElement && countElement) {
                if (count > 0) {
                    countElement.textContent = count;
                    counterElement.classList.remove('hidden');
                } else {
                    counterElement.classList.add('hidden');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('token');
            if (!token) {
                window.location.href = '{{ route("login") }}';
                return;
            }

            // Prevenir submit del formulario de reserva
            const reservaForm = document.getElementById('reserva-form');
            if (reservaForm) {
                reservaForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Submit del formulario prevenido');
                    return false;
                });
            }

            // Primero cargar datos del usuario para obtener el rol
            loadUserData().then(() => {
                // Una vez que tenemos el rol, cargar el resto del dashboard
                loadStats();
                loadActions();
                loadList();

                // Cargar contador de reservas si hay token (usuario autenticado)
                loadReservationsCounter();
            }).catch(error => {
                console.error('Error inicializando dashboard:', error);
                localStorage.removeItem('token');
                window.location.href = '{{ route("login") }}';
            });
        });

        function loadUserData() {
            return fetch('http://127.0.0.1:8000/api/me', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('user-name').textContent = data.usuario.Nombre;
                    localStorage.setItem('user_role', data.usuario.Rol);
                    console.log('👤 Usuario cargado:', data.usuario.Nombre, '- Rol:', data.usuario.Rol);
                } else {
                    throw new Error('Respuesta no exitosa del API /me');
                }
            })
            .catch(error => {
                console.error('❌ Error cargando datos del usuario:', error);
                localStorage.removeItem('token');
                window.location.href = '{{ route("login") }}';
                throw error; // Re-lanzar el error para que sea capturado por el .catch() del DOMContentLoaded
            });
        }

        function loadStats() {
            const role = localStorage.getItem('user_role');
            const statsSection = document.getElementById('stats-section');

            console.log('📊 Cargando estadísticas para rol:', role);

            if (role === 'Turista') {
                // Estadísticas para turista
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
                });
            } else if (role === 'Guía Turístico') {
                // Estadísticas para guía
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
                });
            } else if (role === 'Administrador') {
                // Estadísticas para administrador
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
                });
            }
        }

        function loadActions() {
            const role = localStorage.getItem('user_role');
            const actionsSection = document.getElementById('actions-section');

            console.log('🎯 Cargando acciones para rol:', role);

            if (role === 'Turista') {
                console.log('✅ Cargando acciones de turista: Reservar Actividades');
                actionsSection.innerHTML = `
                    <h3 class="text-2xl font-bold mb-6 text-gray-800">Mis Acciones</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <button onclick="showActivities()" class="bg-blue-600 text-white p-6 rounded-lg hover:bg-blue-700 transition text-center">
                            <h4 class="text-xl font-semibold mb-2">Reservar Actividades</h4>
                            <p>Descubre y reserva nuevas aventuras</p>
                        </button>
                        <button onclick="showReservations()" class="bg-green-600 text-white p-6 rounded-lg hover:bg-green-700 transition text-center">
                            <h4 class="text-xl font-semibold mb-2">Mis Reservas</h4>
                            <p>Gestiona tus reservas activas</p>
                        </button>
                        <a href="{{ route('search') }}" class="bg-purple-600 text-white p-6 rounded-lg hover:bg-purple-700 transition text-center">
                            <h4 class="text-xl font-semibold mb-2">Buscar Más</h4>
                            <p>Explora todas las opciones disponibles</p>
                        </a>
                    </div>
                `;
            } else if (role === 'Guía Turístico') {
                actionsSection.innerHTML = `
                    <h3 class="text-2xl font-bold mb-6 text-gray-800">Panel de Guía</h3>
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
                `;
            } else if (role === 'Administrador') {
                actionsSection.innerHTML = `
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
                    </div>
                `;
            }
        }

        function loadList() {
            const role = localStorage.getItem('user_role');
            const listSection = document.getElementById('list-section');

            console.log('📋 Cargando lista para rol:', role);

            if (role === 'Turista') {
                console.log('✅ Cargando lista de reservas para turista');
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
                });
            } else if (role === 'Guía Turístico') {
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
                                    <img src="${actividad.Imagen || 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'}" alt="${actividad.Nombre_Actividad}" class="w-full h-32 object-cover rounded mb-2" onerror="this.src='https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'">
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
                });
            } else if (role === 'Administrador') {
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
                });
            }
        }

        // Funciones de acción (placeholders)
        function createActivity() { alert('Función para crear actividad'); }
        function manageActivities() { alert('Función para gestionar actividades'); }
        function viewReservations() { alert('Función para ver reservas'); }
        function manageUsers() { alert('Función para gestionar usuarios'); }
        function manageCategories() { alert('Función para gestionar categorías'); }
        function viewReports() { alert('Función para ver reportes'); }
        function showReservations() {
            const listSection = document.getElementById('list-section');

            // Cambiar el título y contenido
            listSection.innerHTML = `
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">Mis Reservas</h3>
                    <button onclick="volverAInicio()" class="text-blue-600 hover:text-blue-800">Volver al inicio</button>
                </div>
                <div id="reservations-content">
                    <div class="text-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                        <p class="mt-2 text-gray-600">Cargando reservas...</p>
                    </div>
                </div>
            `;

            // Cargar reservas
            fetchWithAuth('http://127.0.0.1:8000/api/turista/reservas')
            .then(response => response.json())
            .then(data => {
                const contentElement = document.getElementById('reservations-content');

                if (data.success) {
                    let html = '';

                    // Reservas próximas
                    if (data.reservas.proximas.length > 0) {
                        html += '<h4 class="text-lg font-semibold mb-4 text-blue-600">Próximas Reservas</h4>';
                        html += '<div class="space-y-4 mb-8">';
                        data.reservas.proximas.forEach(reserva => {
                            html += createReservaCard(reserva);
                        });
                        html += '</div>';
                    }

                    // Reservas pasadas
                    if (data.reservas.pasadas.length > 0) {
                        html += '<h4 class="text-lg font-semibold mb-4 text-green-600">Reservas Pasadas</h4>';
                        html += '<div class="space-y-4">';
                        data.reservas.pasadas.forEach(reserva => {
                            html += createReservaCard(reserva);
                        });
                        html += '</div>';
                    }

                    if (data.reservas.pasadas.length === 0 && data.reservas.proximas.length === 0) {
                        html += '<p class="text-gray-600 text-center py-8">No tienes reservas aún.</p>';
                    }

                    contentElement.innerHTML = html;
                } else {
                    contentElement.innerHTML = '<p class="text-red-500 text-center py-8">Error al cargar las reservas.</p>';
                }
            })
            .catch(error => {
                console.error('Error cargando reservas:', error);
                document.getElementById('reservations-content').innerHTML =
                    '<p class="text-red-500 text-center py-8">Error de conexión al cargar las reservas.</p>';
            });
        }

        function createReservaCard(reserva) {
            const fecha = new Date(reserva.fecha_reserva).toLocaleDateString('es-ES');
            const hora = reserva.hora ? reserva.hora.substring(0, 5) : 'N/A';

            let estadoClass = 'bg-gray-100 text-gray-800';
            let estadoText = reserva.estado;

            switch (reserva.estado) {
                case 'confirmada':
                    estadoClass = 'bg-green-100 text-green-800';
                    estadoText = 'Confirmada';
                    break;
                case 'pendiente':
                    estadoClass = 'bg-yellow-100 text-yellow-800';
                    estadoText = 'Pendiente';
                    break;
                case 'cancelada':
                    estadoClass = 'bg-red-100 text-red-800';
                    estadoText = 'Cancelada';
                    break;
            }

            return `
                <div class="bg-gray-50 rounded-lg p-4 border">
                    <div class="flex justify-between items-start mb-2">
                        <h5 class="font-semibold text-gray-800">${reserva.actividad.nombre_actividad}</h5>
                        <span class="px-2 py-1 rounded-full text-xs font-medium ${estadoClass}">${estadoText}</span>
                    </div>
                    <div class="text-sm text-gray-600 space-y-1">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            ${fecha} a las ${hora}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            ${reserva.actividad.ubicacion}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            ${reserva.numero_personas} persona${reserva.numero_personas > 1 ? 's' : ''}
                        </div>
                        ${reserva.notas ? `
                        <div class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span class="text-xs italic">${reserva.notas}</span>
                        </div>
                        ` : ''}
                    </div>
                    <div class="mt-3 text-right">
                        <span class="text-lg font-bold text-blue-600">$${reserva.actividad.precio * reserva.numero_personas}</span>
                    </div>
                </div>
            `;
        }
        function showActivities() {
            const listSection = document.getElementById('list-section');

            // Cambiar el título y contenido
            listSection.innerHTML = `
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">Reservar Actividades</h3>
                    <button onclick="volverAInicio()" class="text-blue-600 hover:text-blue-800">Volver al inicio</button>
                </div>
                <div id="activities-content">
                    <div class="text-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                        <p class="mt-2 text-gray-600">Cargando actividades...</p>
                    </div>
                </div>
            `;

            // Cargar actividades disponibles
            fetch('http://127.0.0.1:8000/api/listarActividades')
            .then(response => response.json())
            .then(data => {
                const contentElement = document.getElementById('activities-content');

                if (Array.isArray(data) && data.length > 0) {
                    let html = '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">';
                    data.forEach(actividad => {
                        html += createActivityCard(actividad);
                    });
                    html += '</div>';
                    contentElement.innerHTML = html;
                } else {
                    contentElement.innerHTML = '<p class="text-gray-600 text-center py-8">No hay actividades disponibles en este momento.</p>';
                }
            })
            .catch(error => {
                console.error('Error cargando actividades:', error);
                document.getElementById('activities-content').innerHTML =
                    '<p class="text-red-500 text-center py-8">Error al cargar las actividades.</p>';
            });
        }

        function createActivityCard(actividad) {
            const fecha = new Date(actividad.Fecha_Actividad).toLocaleDateString('es-ES');
            const hora = actividad.Hora_Actividad ? actividad.Hora_Actividad.substring(0, 5) : 'N/A';

            return `
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                    <img src="${actividad.Imagen || 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'}"
                         alt="${actividad.Nombre_Actividad}"
                         class="w-full h-48 object-cover"
                         onerror="this.src='https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'">
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2 text-gray-800">${actividad.Nombre_Actividad}</h3>
                        <p class="text-gray-600 mb-4 line-clamp-3">${actividad.Descripcion}</p>
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                ${fecha} a las ${hora}
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                ${actividad.Ubicacion}
                            </div>
                        </div>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-2xl font-bold text-blue-600">$${actividad.Precio}</span>
                            <span class="text-sm text-gray-500">Máx. ${actividad.Cupo_Maximo} personas</span>
                        </div>
                        <button onclick="openReservationModal(${actividad.id}, '${actividad.Nombre_Actividad}')"
                                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                            Reservar Ahora
                        </button>
                    </div>
                </div>
            `;
        }

        function openReservationModal(activityId, activityName) {
            // Verificar autenticación
            const token = localStorage.getItem('token');
            if (!token) {
                alert('Debes iniciar sesión para reservar una actividad.');
                window.location.href = '{{ route("login") }}';
                return;
            }

            // Obtener información del usuario y actividad
            Promise.all([
                fetch('http://127.0.0.1:8000/api/me', {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                }),
                fetch(`http://127.0.0.1:8000/api/actividades/${activityId}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
            ])
            .then(([userResponse, activityResponse]) => Promise.all([userResponse.json(), activityResponse.json()]))
            .then(([userData, activityData]) => {
                console.log('👤 Respuesta de /api/me:', userData);
                console.log('🎯 Respuesta de actividad:', activityData);

                // Verificar estructura de respuesta
                if (!userData.success) {
                    console.error('❌ API /me no fue exitoso:', userData);
                    alert('Error: No se pudo obtener información del usuario.');
                    return;
                }

                if (!userData.usuario) {
                    console.error('❌ No se encontró usuario en respuesta:', userData);
                    alert('Error: Usuario no encontrado.');
                    return;
                }

                if (!userData.usuario.id) {
                    console.error('❌ Usuario no tiene ID:', userData.usuario);
                    alert('Error: ID de usuario no válido.');
                    return;
                }

                if (!activityData.id) {
                    console.error('❌ Actividad no válida:', activityData);
                    alert('Error: Actividad no encontrada.');
                    return;
                }

                // Llenar el modal con datos
                document.getElementById('actividad-id').value = activityId;
                // Usar el ID del perfil de turista (usuarios table) si existe, sino el ID del usuario
                const usuarioId = userData.usuario.userable_id || userData.usuario.id;
                document.getElementById('usuario-id').value = usuarioId;
                document.getElementById('modal-title').textContent = `Reservar: ${activityName}`;

                console.log('📝 ID Usuario asignado:', userData.usuario.id);
                console.log('📝 ID Actividad asignada:', activityId);

                // Mostrar detalles de la actividad
                const activityDetails = document.getElementById('actividad-detalles');
                const fecha = new Date(activityData.Fecha_Actividad).toLocaleDateString('es-ES');
                const hora = activityData.Hora_Actividad ? activityData.Hora_Actividad.substring(0, 5) : 'N/A';

                activityDetails.innerHTML = `
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div><strong>📅 Fecha:</strong> ${fecha}</div>
                        <div><strong>🕐 Hora:</strong> ${hora}</div>
                        <div><strong>📍 Ubicación:</strong> ${activityData.Ubicacion}</div>
                        <div><strong>💰 Precio por persona:</strong> $${activityData.Precio}</div>
                        <div><strong>👥 Cupo máximo:</strong> ${activityData.Cupo_Maximo} personas</div>
                        <div><strong>📝 Descripción:</strong> ${activityData.Descripcion}</div>
                    </div>
                `;

                // Mostrar modal
                console.log('📱 Abriendo modal de reserva...');
                document.getElementById('reserva-modal').classList.remove('hidden');
                console.log('✅ Modal de reserva abierto');
            })
            .catch(error => {
                console.error('Error obteniendo datos:', error);
                alert('Error de conexión. Inténtalo nuevamente.');
            });
        }

        function closeReservaModal() {
            document.getElementById('reserva-modal').classList.add('hidden');
        }

        function makeReservation() {
            console.log('🚀 Iniciando proceso de reserva desde botón...');

            // Mostrar alerta de confirmación
            console.log('🔔 Mostrando alerta de confirmación...');
            const confirmacion = confirm('¿Estás seguro de que deseas confirmar esta reserva? Una vez confirmada, recibirás un email con los detalles.');

            if (!confirmacion) {
                console.log('❌ Reserva cancelada por el usuario');
                return;
            }

            console.log('✅ Reserva confirmada por el usuario - procesando...');
            console.log('📋 Formulario encontrado:', !!document.getElementById('reserva-form'));

            const form = document.getElementById('reserva-form');
            const formData = new FormData(form);

            // Validar datos básicos
            const numeroPersonas = parseInt(formData.get('Numero_Personas'));
            console.log('📝 Validando número de personas:', numeroPersonas);

            if (!numeroPersonas || numeroPersonas < 1 || numeroPersonas > 10) {
                console.error('❌ Error de validación: Número de personas inválido');
                alert('El número de personas debe estar entre 1 y 10.');
                return;
            }

            // Preparar datos para envío
            const data = {
                idUsuario: parseInt(formData.get('idUsuario')),
                idActividad: parseInt(formData.get('idActividad')),
                Numero_Personas: numeroPersonas,
                Estado: formData.get('Estado') || 'pendiente',
                notas: formData.get('notas') || ''
            };

            console.log('📦 Datos preparados para reserva:', data);
            console.log('🔐 Token disponible:', !!localStorage.getItem('token'));
            console.log('🌐 Enviando solicitud a API...');

            // Mostrar loading
            const submitButton = document.querySelector('#reserva-form button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.textContent = 'Procesando...';
            submitButton.disabled = true;

            fetchWithAuth('http://127.0.0.1:8000/api/crearReservas', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                console.log('📡 Respuesta del servidor recibida');
                console.log('📊 Código de estado HTTP:', response.status);
                console.log('📋 Headers de respuesta:', Object.fromEntries(response.headers.entries()));

                // Verificar si la respuesta es JSON
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    // Si no es JSON, intentar parsear como texto
                    return response.text().then(text => {
                        throw new Error('Respuesta no JSON del servidor: ' + text);
                    });
                }
            })
            .then(data => {
                console.log('📄 Datos JSON de respuesta:', data);
                console.log('✅ Éxito de la operación:', data.success);

                if (data.success && data.reserva) {
                    console.log('🎉 Reserva creada exitosamente!');
                    console.log('🆔 ID de reserva:', data.reserva.id);
                    console.log('📧 Enviando confirmación por email...');

                    alert('¡Reserva creada exitosamente! Revisa tu email para la confirmación.');
                    closeReservaModal();

                    // Limpiar formulario
                    console.log('🧹 Limpiando formulario...');
                    form.reset();

                    // Actualizar contador de reservas
                    console.log('🔄 Actualizando contador de reservas...');
                    loadReservationsCounter();

                    // Actualizar estadísticas
                    console.log('📊 Actualizando estadísticas...');
                    loadStats();

                    // Actualizar lista de reservas si está visible
                    console.log('📋 Actualizando lista de reservas...');
                    if (document.getElementById('reservations-content')) {
                        showReservations();
                    }

                    // Mostrar mensaje de éxito
                    console.log('✅ Mostrando notificación de éxito...');
                    showNotification('Reserva creada exitosamente', 'success');
                } else {
                    console.error('❌ Error en la respuesta del servidor');
                    console.log('🔍 Analizando errores...');

                    // Mostrar errores específicos
                    if (data.errors) {
                        console.error('🚫 Errores de validación:', data.errors);
                        let errorMessage = 'Errores de validación:\n';
                        for (let field in data.errors) {
                            errorMessage += `${field}: ${data.errors[field].join(', ')}\n`;
                        }
                        alert(errorMessage);
                    } else if (data.message) {
                        console.error('⚠️ Mensaje de error:', data.message);
                        alert('Error: ' + data.message);
                    } else {
                        console.error('❓ Error desconocido en respuesta:', data);
                        alert('Error desconocido al crear la reserva. Revisa la consola para más detalles.');
                    }
                }
            })
            .catch(error => {
                console.error('💥 Error procesando reserva:', error);
                console.error('Stack trace:', error.stack);

                // Mostrar error al usuario sin recargar página
                let errorMessage = 'Error al procesar la reserva. ';
                if (error.message.includes('fetch')) {
                    errorMessage += 'Verifica tu conexión a internet.';
                } else if (error.message.includes('401')) {
                    errorMessage += 'Tu sesión ha expirado. Inicia sesión nuevamente.';
                    localStorage.removeItem('token');
                    setTimeout(() => window.location.href = '{{ route("login") }}', 2000);
                } else {
                    errorMessage += 'Inténtalo nuevamente.';
                }

                alert(errorMessage);
            })
            .finally(() => {
                // Restaurar botón
                submitButton.textContent = originalText;
                submitButton.disabled = false;
            });
        }

        function showNotification(message, type = 'info') {
            // Crear notificación temporal
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 ${
                type === 'success' ? 'bg-green-500' :
                type === 'error' ? 'bg-red-500' : 'bg-blue-500'
            }`;
            notification.textContent = message;

            document.body.appendChild(notification);

            // Remover después de 3 segundos
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 3000);
        }

        function volverAInicio() {
            // Recargar el dashboard completo
            loadStats();
            loadActions();
            loadList();
        }

        function editUser(id) { alert('Editar usuario ' + id); }
        function deleteUser(id) { alert('Eliminar usuario ' + id); }
    </script>

    <!-- Modal de Reserva -->
    <div id="reserva-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 id="modal-title" class="text-2xl font-bold text-gray-800">Reservar Actividad</h2>
                    <button onclick="closeReservaModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div id="actividad-detalles" class="mb-6">
                    <!-- Detalles de la actividad se cargarán aquí -->
                </div>

                <form id="reserva-form" class="space-y-4" onsubmit="event.preventDefault(); return false;">
                    <input type="hidden" id="actividad-id" name="idActividad">
                    <input type="hidden" id="usuario-id" name="idUsuario">

                    <div>
                        <label for="numero-personas" class="block text-sm font-medium text-gray-700 mb-2">
                            Número de Personas
                        </label>
                        <input type="number" id="numero-personas" name="Numero_Personas" min="1" max="10"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                        <p class="text-xs text-gray-500 mt-1">Máximo 10 personas por reserva</p>
                    </div>

                    <div>
                        <label for="estado-reserva" class="block text-sm font-medium text-gray-700 mb-2">
                            Estado de la Reserva
                        </label>
                        <select id="estado-reserva" name="Estado"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="pendiente">Pendiente</option>
                            <option value="confirmada">Confirmada</option>
                        </select>
                    </div>

                    <div>
                        <label for="notas-reserva" class="block text-sm font-medium text-gray-700 mb-2">
                            Notas Adicionales (Opcional)
                        </label>
                        <textarea id="notas-reserva" name="notas" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="¿Alguna petición especial o información adicional?"></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeReservaModal()"
                                class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                            Cancelar
                        </button>
                        <button type="button" onclick="makeReservation()"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                            Confirmar Reserva
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>