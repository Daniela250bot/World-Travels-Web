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
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-blue-600">WORLD TRAVELS</h1>
            <nav class="flex items-center space-x-6">
                <a href="{{ route('search') }}" class="text-gray-700 hover:text-blue-600 transition">Buscar Actividades</a>
                @auth
                    <div id="reservations-counter" class="hidden bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-sm font-semibold">
                        <span id="active-reservations-count">0</span> reservas activas
                    </div>
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 transition">Inicio</a>
                    <button onclick="showSection('perfil')" class="text-gray-700 hover:text-blue-600 transition bg-transparent border-none cursor-pointer">Mi Perfil</button>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-blue-600 transition bg-transparent border-none cursor-pointer">Cerrar Sesión</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" class="text-gray-700 hover:text-blue-600 transition">Registrarse</a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-blue-600 via-teal-500 to-green-500 text-white py-20 overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="absolute inset-0" style="background-image: url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80'); background-size: cover; background-position: center;"></div>
        <div class="relative container mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold mb-4">Descubre Boyacá</h1>
            <p class="text-xl mb-8 max-w-2xl mx-auto">Explora las mejores experiencias turísticas y actividades en el corazón de Colombia</p>

            <!-- Formulario de búsqueda mejorado -->
            <div class="max-w-2xl mx-auto mb-8">
                <form id="search-form" class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-6">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <input type="text" id="query" name="query"
                                   placeholder="Buscar actividades, lugares, experiencias..."
                                   class="w-full px-4 py-3 border-0 rounded-lg text-gray-800 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                        <button type="submit"
                                class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
                            🔍 Buscar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Estadísticas rápidas -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4">
                    <div class="text-3xl font-bold mb-1" id="stats-actividades">0</div>
                    <div class="text-sm opacity-90">Actividades</div>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4">
                    <div class="text-3xl font-bold mb-1" id="stats-empresas">0</div>
                    <div class="text-sm opacity-90">Empresas</div>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4">
                    <div class="text-3xl font-bold mb-1" id="stats-destinos">0</div>
                    <div class="text-sm opacity-90">Destinos</div>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4">
                    <div class="text-3xl font-bold mb-1" id="stats-resenas">0</div>
                    <div class="text-sm opacity-90">Reseñas</div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" class="w-full h-12 fill-white">
                <path d="M0,32L48,37.3C96,43,192,53,288,58.7C384,64,480,64,576,58.7C672,53,768,43,864,48C960,53,1056,75,1152,80C1248,85,1344,75,1392,69.3L1440,64L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z"></path>
            </svg>
        </div>
    </section>

    <main class="container mx-auto px-4 py-12 -mt-12 relative z-10">
        <!-- Filtros y categorías -->
        <section class="mb-12">
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <h2 class="text-3xl font-bold text-gray-800">Actividades Disponibles</h2>
                    <div class="flex flex-wrap gap-4">
                        <select id="categoria-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Todas las categorías</option>
                        </select>
                        <select id="ubicacion-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Todas las ubicaciones</option>
                        </select>
                        <button onclick="clearFilters()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition duration-300">
                            Limpiar Filtros
                        </button>
                    </div>
                </div>
            </div>

            <!-- Resultados -->
            <div id="results" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Resultados se mostrarán aquí -->
            </div>

            <!-- Estado vacío -->
            <div id="no-results" class="hidden text-center py-16">
                <div class="text-gray-400 text-8xl mb-6">🔍</div>
                <h3 class="text-2xl font-bold text-gray-600 mb-2">No se encontraron actividades</h3>
                <p class="text-gray-500 mb-6">Intenta con otros términos de búsqueda o filtra por categoría</p>
                <button onclick="clearFilters()" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                    Ver todas las actividades
                </button>
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

    <!-- Variable para JavaScript -->
    <meta name="is-authenticated" content="{{ $isAuthenticated ? 'true' : 'false' }}">

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
        window.isAuthenticated = document.querySelector('meta[name="is-authenticated"]').getAttribute('content') === 'true';
        const isAuthenticated = window.isAuthenticated;

        document.addEventListener('DOMContentLoaded', function() {
            loadAllActivities();
            loadStats();
            loadCategorias();

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

            // Event listeners para filtros
            document.getElementById('categoria-filter').addEventListener('change', applyFilters);
            document.getElementById('ubicacion-filter').addEventListener('change', applyFilters);
        });

        function loadAllActivities() {
            fetch('http://127.0.0.1:8000/api/listarActividades')
                .then(response => response.json())
                .then(data => {
                    displayActivities(data);
                    populateUbicacionFilter(data);
                })
                .catch(error => console.error('Error cargando actividades:', error));
        }

        function loadStats() {
            // Cargar estadísticas para la sección hero
            fetch('http://127.0.0.1:8000/api/listarActividades')
                .then(response => response.json())
                .then(data => {
                    const actividades = data.length;
                    document.getElementById('stats-actividades').textContent = actividades;

                    // Contar empresas únicas
                    const empresas = [...new Set(data.map(a => a.empresa_id))].length;
                    document.getElementById('stats-empresas').textContent = empresas;

                    // Contar ubicaciones únicas
                    const ubicaciones = [...new Set(data.map(a => a.Ubicacion))].length;
                    document.getElementById('stats-destinos').textContent = ubicaciones;

                    // Simular reseñas (esto podría venir de una API real)
                    document.getElementById('stats-resenas').textContent = Math.floor(actividades * 2.5);
                })
                .catch(error => console.error('Error cargando estadísticas:', error));
        }

        function loadCategorias() {
            fetch('http://127.0.0.1:8000/api/categories/active')
                .then(response => response.json())
                .then(data => {
                    const categoriaFilter = document.getElementById('categoria-filter');
                    categoriaFilter.innerHTML = '<option value="">Todas las categorías</option>';

                    if (Array.isArray(data)) {
                        data.forEach(categoria => {
                            const option = document.createElement('option');
                            option.value = categoria.id;
                            option.textContent = categoria.nombre;
                            categoriaFilter.appendChild(option);
                        });
                    }
                })
                .catch(error => console.error('Error cargando categorías:', error));
        }

        function populateUbicacionFilter(actividades) {
            const ubicacionFilter = document.getElementById('ubicacion-filter');
            const ubicaciones = [...new Set(actividades.map(a => a.Ubicacion))].sort();

            ubicacionFilter.innerHTML = '<option value="">Todas las ubicaciones</option>';
            ubicaciones.forEach(ubicacion => {
                const option = document.createElement('option');
                option.value = ubicacion;
                option.textContent = ubicacion;
                ubicacionFilter.appendChild(option);
            });
        }

        function applyFilters() {
            const categoriaId = document.getElementById('categoria-filter').value;
            const ubicacion = document.getElementById('ubicacion-filter').value;
            const query = document.getElementById('query').value;

            fetch('http://127.0.0.1:8000/api/listarActividades')
                .then(response => response.json())
                .then(data => {
                    let filtered = data;

                    // Filtrar por búsqueda
                    if (query) {
                        filtered = filtered.filter(activity =>
                            activity.Nombre_Actividad.toLowerCase().includes(query.toLowerCase()) ||
                            activity.Descripcion.toLowerCase().includes(query.toLowerCase()) ||
                            activity.Ubicacion.toLowerCase().includes(query.toLowerCase())
                        );
                    }

                    // Filtrar por categoría
                    if (categoriaId) {
                        filtered = filtered.filter(activity => activity.categoria_id == categoriaId);
                    }

                    // Filtrar por ubicación
                    if (ubicacion) {
                        filtered = filtered.filter(activity => activity.Ubicacion === ubicacion);
                    }

                    displayActivities(filtered);
                })
                .catch(error => console.error('Error aplicando filtros:', error));
        }

        function clearFilters() {
            document.getElementById('query').value = '';
            document.getElementById('categoria-filter').value = '';
            document.getElementById('ubicacion-filter').value = '';
            loadAllActivities();
        }

        function searchActivities(query) {
            // Actualizar el campo de búsqueda y aplicar filtros
            document.getElementById('query').value = query;
            applyFilters();
        }

        function displayActivities(activities) {
            const results = document.getElementById('results');
            const noResults = document.getElementById('no-results');

            results.innerHTML = '';

            if (activities.length === 0) {
                results.classList.add('hidden');
                noResults.classList.remove('hidden');
                return;
            }

            results.classList.remove('hidden');
            noResults.classList.add('hidden');

            activities.forEach(activity => {
                const div = document.createElement('div');
                div.className = 'bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 transform hover:-translate-y-2 group';

                div.innerHTML = `
                    <div class="relative overflow-hidden rounded-t-2xl">
                        <img src="${activity.Imagen || 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'}"
                             alt="${activity.Nombre_Actividad}"
                             class="w-full h-56 object-cover transition-transform duration-500 group-hover:scale-110"
                             onerror="this.src='https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'">
                        <div class="absolute top-4 right-4 bg-white bg-opacity-90 backdrop-blur-sm rounded-full px-3 py-1 shadow-lg">
                            <span class="text-sm font-bold text-gray-800">$${activity.Precio}</span>
                        </div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <div class="bg-gradient-to-t from-black to-transparent rounded-lg p-4">
                                <h3 class="text-xl font-bold text-white mb-1 line-clamp-2">${activity.Nombre_Actividad}</h3>
                                <div class="flex items-center text-white text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    ${activity.Ubicacion}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">${activity.Descripcion}</p>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <div class="font-medium">${new Date(activity.Fecha_Actividad).toLocaleDateString('es-ES')}</div>
                                    <div class="text-xs">${activity.Hora_Actividad.substring(0, 5)}</div>
                                </div>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                <div>
                                    <div class="font-medium">Máx. ${activity.Cupo_Maximo}</div>
                                    <div class="text-xs">personas</div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="flex text-yellow-400 mr-2">
                                    ★★★★☆
                                </div>
                                <span class="text-sm text-gray-500">(4.2)</span>
                            </div>
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">4-6 horas</span>
                        </div>

                        <div class="flex space-x-3">
                            <button onclick="openReservationModal(${activity.id}, '${activity.Nombre_Actividad}')"
                                    class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
                                Reservar Ahora
                            </button>
                            <button onclick="viewReviews(${activity.id})"
                                    class="px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </button>
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