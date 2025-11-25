<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Actividades - WORLD TRAVELS en Boyacá</title>
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
            .container { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }
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
            .grid { display: grid; }
            .grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
            .md\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .lg\:grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .gap-4 { gap: 1rem; }
            .h-48 { height: 12rem; }
            .object-cover { object-fit: cover; }
            .text-xl { font-size: 1.25rem; }
        </style>
    @endif
</head>
<body class="bg-gray-100">
    <header class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">WORLD TRAVELS en Boyacá</h1>
            <nav class="flex items-center">
                <a href="{{ route('home') }}" class="mr-4">Inicio</a>
                <a href="{{ route('search') }}" class="mr-4">Buscar Actividades</a>
                @auth
                    <div id="reservations-counter" class="hidden bg-white text-blue-600 px-3 py-1 rounded-full text-sm font-semibold mr-4">
                        <span id="active-reservations-count">0</span> reservas activas
                    </div>
                    <a href="{{ route('dashboard') }}" class="mr-4">Mi Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="bg-transparent border-none cursor-pointer text-white hover:text-gray-300">Cerrar Sesión</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="mr-4">Iniciar Sesión</a>
                    <a href="{{ route('register') }}">Registrarse</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="container mx-auto p-4">
        <section class="mb-8">
            <h2 class="text-3xl font-bold text-center mb-4">Buscar Actividades</h2>
            <form id="search-form" class="bg-white p-6 rounded shadow mb-6">
                <div class="mb-4">
                    <label for="query" class="block mb-2">Buscar por nombre o descripción</label>
                    <input type="text" id="query" name="query" class="w-full px-3 py-2 border rounded-md">
                </div>
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Buscar</button>
            </form>
            <div id="results" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Resultados se mostrarán aquí -->
            </div>
        </section>

        <!-- Sección de Mis Reservas (solo para usuarios autenticados) -->
        @auth
        <section id="my-reservations-section" class="mb-8">
            <div class="bg-white p-6 rounded shadow">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-2xl font-bold text-gray-800">Mis Reservas</h3>
                    <button onclick="toggleReservationsSection()" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                        <span id="toggle-text">Mostrar</span> Reservas
                    </button>
                </div>
                <div id="reservations-container" class="hidden">
                    <div id="reservations-loading" class="text-center py-4">
                        <p class="text-gray-500">Cargando reservas...</p>
                    </div>
                    <div id="reservations-content">
                        <!-- Las reservas se cargarán aquí -->
                    </div>
                </div>
            </div>
        </section>
        @endauth
    </main>

    @php
        $isAuthenticated = auth()->check();
    @endphp

    <!-- Modal de Reserva -->
    <div id="reservation-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4" id="modal-title">Reservar Actividad</h3>
                <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-800 mb-2"><strong>Detalles de la actividad:</strong></p>
                    <div id="activity-details" class="text-sm text-blue-700 space-y-1">
                        <!-- Los detalles se cargarán dinámicamente -->
                    </div>
                </div>
                <form id="reservation-form">
                    <input type="hidden" id="activity-id" name="idActividad">
                    <input type="hidden" id="user-id" name="idUsuario">
                    <input type="hidden" name="Estado" value="pendiente">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Número de Personas</label>
                        <input type="number" id="numero-personas" name="Numero_Personas" min="1" max="10" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <p class="text-xs text-gray-500 mt-1">Máximo 10 personas por reserva</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="button" onclick="closeReservationModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cancelar</button>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Reservar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        window.isAuthenticated = @json($isAuthenticated);
        const isAuthenticated = window.isAuthenticated;

        document.addEventListener('DOMContentLoaded', function() {
            loadAllActivities();

            // Cargar reservas y contador si el usuario está autenticado
            if (isAuthenticated) {
                loadReservationsCounter();
            }

            document.getElementById('search-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const query = document.getElementById('query').value;
                searchActivities(query);
            });

            // Manejar envío del formulario de reserva
            document.getElementById('reservation-form').addEventListener('submit', function(e) {
                e.preventDefault();
                makeReservation();
            });
        });

        function loadAllActivities() {
            fetch('http://127.0.0.1:8000/api/listarActividades')
                .then(response => response.json())
                .then(data => displayActivities(data))
                .catch(error => console.error('Error cargando actividades:', error));
        }

        function searchActivities(query) {
            fetch('http://127.0.0.1:8000/api/listarActividades')
                .then(response => response.json())
                .then(data => {
                    const filtered = data.filter(activity =>
                        activity.Nombre_Actividad.toLowerCase().includes(query.toLowerCase()) ||
                        activity.Descripcion.toLowerCase().includes(query.toLowerCase()) ||
                        activity.Ubicacion.toLowerCase().includes(query.toLowerCase())
                    );
                    displayActivities(filtered);
                })
                .catch(error => console.error('Error buscando actividades:', error));
        }

        function displayActivities(activities) {
            const results = document.getElementById('results');
            results.innerHTML = '';
            if (activities.length === 0) {
                results.innerHTML = '<p class="col-span-full text-center text-gray-500">No se encontraron actividades.</p>';
                return;
            }
            activities.forEach(activity => {
                const div = document.createElement('div');
                div.className = 'bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300';
                div.innerHTML = `
                    <img src="${activity.Imagen || 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'}" alt="${activity.Nombre_Actividad}" class="w-full h-48 object-cover" onerror="this.src='https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'">
                    <div class="p-6">
                        <h3 class="text-2xl font-bold mb-2 text-gray-800">${activity.Nombre_Actividad}</h3>
                        <p class="text-gray-600 mb-4">${activity.Descripcion}</p>
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                ${new Date(activity.Fecha_Actividad).toLocaleDateString('es-ES')}
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                ${activity.Hora_Actividad.substring(0, 5)}
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                ${activity.Ubicacion}
                            </div>
                        </div>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-2xl font-bold text-blue-600">$${activity.Precio}</span>
                            <span class="text-sm text-gray-500">Máx. ${activity.Cupo_Maximo} personas</span>
                        </div>
                        <div class="mt-4 space-y-2">
                            <button onclick="openReservationModal(${activity.id}, '${activity.Nombre_Actividad}')" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-300">Reservar Ahora</button>
                            <button onclick="viewReviews(${activity.id})" class="w-full bg-gray-600 text-white py-2 rounded-lg hover:bg-gray-700 transition duration-300">Ver Reseñas</button>
                        </div>
                    </div>
                `;
                results.appendChild(div);
            });
        }

        function openReservationModal(activityId, activityName) {
            if (isAuthenticated) {
                document.getElementById('activity-id').value = activityId;
                document.getElementById('modal-title').textContent = `Reservar: ${activityName}`;
                document.getElementById('reservation-modal').classList.remove('hidden');
            } else {
                alert('Debes iniciar sesión para reservar una actividad.');
                window.location.href = '{{ route("login") }}';
            }
        }

        function closeReservationModal() {
            document.getElementById('reservation-modal').classList.add('hidden');
        }

        function viewReviews(activityId) {
            // Abrir modal o sección para ver reseñas
            fetch(`http://127.0.0.1:8000/api/comentarios-reservas?actividad_id=${activityId}`)
                .then(response => response.json())
                .then(data => {
                    // Mostrar reseñas en un modal o alert por ahora
                    let reviewsText = 'Reseñas:\n';
                    data.forEach(review => {
                        reviewsText += `${review.usuario.name}: ${review.comentario} (${review.calificacion} estrellas)\n`;
                        if (review.fotos && review.fotos.length > 0) {
                            reviewsText += `Fotos: ${review.fotos.length}\n`;
                        }
                    });
                    alert(reviewsText || 'No hay reseñas aún.');
                })
                .catch(error => console.error('Error cargando reseñas:', error));
        }

        function makeReservation() {
            const form = document.getElementById('reservation-form');
            const formData = new FormData(form);

            // Obtener información del usuario autenticado
            const token = localStorage.getItem('token');
            if (!token) {
                alert('Tu sesión ha expirado. Por favor, inicia sesión nuevamente.');
                window.location.href = '{{ route("login") }}';
                return;
            }

            // Primero obtener datos del usuario
            fetch('http://127.0.0.1:8000/api/me', {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Respuesta de /api/me:', response.status);
                if (response.status === 401) {
                    alert('Tu sesión ha expirado. Por favor, inicia sesión nuevamente.');
                    localStorage.removeItem('token');
                    window.location.href = '{{ route("login") }}';
                    throw new Error('Unauthorized');
                }
                if (!response.ok) {
                    throw new Error('Error obteniendo datos del usuario');
                }
                return response.json();
            })
            .then(userData => {
                console.log('Datos del usuario:', userData);
                if (!userData.id) {
                    alert('Error al obtener información del usuario.');
                    throw new Error('Usuario no válido');
                }

                // Convertir FormData a objeto con el userId correcto
                const data = {
                    idUsuario: userData.id,
                    idActividad: parseInt(formData.get('idActividad')),
                    Numero_Personas: parseInt(formData.get('Numero_Personas')),
                    Estado: formData.get('Estado')
                };

                console.log('Enviando datos de reserva:', data);

                // Crear la reserva
                return fetch('http://127.0.0.1:8000/api/crearReservas', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
            })
            .then(response => {
                console.log('Respuesta de crear reserva:', response.status);
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Error en la reserva');
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Datos de respuesta:', data);
                if (data.success && data.reserva) {
                    alert('¡Reserva creada exitosamente! Revisa tu email para la confirmación.');
                    closeReservationModal();
                    // Limpiar formulario
                    document.getElementById('reservation-form').reset();
                    // Actualizar contador de reservas
                    loadReservationsCounter();
                } else {
                    // Mostrar errores de validación específicos
                    if (data.errors) {
                        let errorMessage = 'Errores de validación:\n';
                        for (let field in data.errors) {
                            errorMessage += `${field}: ${data.errors[field].join(', ')}\n`;
                        }
                        alert(errorMessage);
                    } else {
                        alert('Error: ' + (data.message || 'Error desconocido'));
                    }
                }
            })
            .catch(error => {
                console.error('Error creando reserva:', error);
                if (error.message !== 'Unauthorized' && error.message !== 'Usuario no válido') {
                    alert('Error de conexión. Inténtalo nuevamente.');
                }
            });
        }

        // Función para cargar el contador de reservas
        function loadReservationsCounter() {
            const token = localStorage.getItem('token');
            if (!token) return;

            fetch('http://127.0.0.1:8000/api/turista/reservas', {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            })
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

            if (count > 0) {
                countElement.textContent = count;
                counterElement.classList.remove('hidden');
            } else {
                counterElement.classList.add('hidden');
            }
        }

        // Función para alternar la visibilidad de la sección de reservas
        function toggleReservationsSection() {
            const container = document.getElementById('reservations-container');
            const toggleText = document.getElementById('toggle-text');

            if (container.classList.contains('hidden')) {
                container.classList.remove('hidden');
                toggleText.textContent = 'Ocultar';
                loadUserReservations();
            } else {
                container.classList.add('hidden');
                toggleText.textContent = 'Mostrar';
            }
        }

        // Función para cargar las reservas del usuario
        function loadUserReservations() {
            const loadingElement = document.getElementById('reservations-loading');
            const contentElement = document.getElementById('reservations-content');

            loadingElement.classList.remove('hidden');
            contentElement.innerHTML = '';

            const token = localStorage.getItem('token');
            if (!token) {
                loadingElement.innerHTML = '<p class="text-red-500">Sesión expirada. Recarga la página.</p>';
                return;
            }

            fetch('http://127.0.0.1:8000/api/turista/reservas', {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                loadingElement.classList.add('hidden');

                if (data.success) {
                    displayUserReservations(data.reservas);
                } else {
                    contentElement.innerHTML = '<p class="text-red-500">Error al cargar las reservas.</p>';
                }
            })
            .catch(error => {
                console.error('Error cargando reservas:', error);
                loadingElement.classList.add('hidden');
                contentElement.innerHTML = '<p class="text-red-500">Error de conexión al cargar las reservas.</p>';
            });
        }

        // Función para mostrar las reservas del usuario
        function displayUserReservations(reservas) {
            const contentElement = document.getElementById('reservations-content');

            if (reservas.proximas.length === 0 && reservas.pasadas.length === 0) {
                contentElement.innerHTML = '<p class="text-gray-500 text-center py-4">No tienes reservas aún.</p>';
                return;
            }

            let html = '';

            // Reservas próximas
            if (reservas.proximas.length > 0) {
                html += '<h4 class="text-lg font-semibold text-blue-600 mb-3">Próximas Reservas</h4>';
                html += '<div class="space-y-3 mb-6">';
                reservas.proximas.forEach(reserva => {
                    html += createReservationCard(reserva);
                });
                html += '</div>';
            }

            // Reservas pasadas
            if (reservas.pasadas.length > 0) {
                html += '<h4 class="text-lg font-semibold text-green-600 mb-3">Reservas Pasadas</h4>';
                html += '<div class="space-y-3">';
                reservas.pasadas.forEach(reserva => {
                    html += createReservationCard(reserva);
                });
                html += '</div>';
            }

            contentElement.innerHTML = html;
        }

        // Función para crear una tarjeta de reserva
        function createReservationCard(reserva) {
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
    </script>
</body>
</html>