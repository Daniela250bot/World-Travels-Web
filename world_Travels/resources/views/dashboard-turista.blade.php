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

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-blue-600 via-teal-500 to-green-500 text-white py-20 overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="absolute inset-0" style="background-image: url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80'); background-size: cover; background-position: center;"></div>
        <div class="relative container mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold mb-4">¡Bienvenido a World Travels!</h1>
            <p class="text-xl mb-8 max-w-2xl mx-auto">Descubre las mejores experiencias turísticas en Boyacá. Explora empresas locales y reserva actividades inolvidables.</p>
            <div class="flex justify-center space-x-4">
                <button onclick="scrollToEmpresas()" class="bg-white text-blue-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition duration-300 transform hover:scale-105">
                    Explorar Empresas
                </button>
                <button onclick="showSection('reservas')" class="border-2 border-white text-white px-8 py-3 rounded-full font-semibold hover:bg-white hover:text-blue-600 transition duration-300">
                    Mis Reservas
                </button>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" class="w-full h-12 fill-white">
                <path d="M0,32L48,37.3C96,43,192,53,288,58.7C384,64,480,64,576,58.7C672,53,768,43,864,48C960,53,1056,75,1152,80C1248,85,1344,75,1392,69.3L1440,64L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z"></path>
            </svg>
        </div>
    </section>

    <main class="container mx-auto px-4 py-12 -mt-12 relative z-10">
        <!-- Estadísticas rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12" id="stats-section">
            <!-- Estadísticas se cargarán aquí -->
        </div>

        <!-- Empresas Activas -->
        <section id="empresas-section" class="mb-16">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-4xl font-bold text-gray-800">Empresas Destacadas</h2>
                <div class="flex space-x-4">
                    <select id="categoria-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Todas las categorías</option>
                        <!-- Opciones se cargarán dinámicamente -->
                    </select>
                </div>
            </div>
            <div id="empresas-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Empresas se cargarán aquí -->
            </div>
        </section>

        <!-- Viajes por el Boyacá -->
        <section id="viajes-section" class="mb-16">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-4xl font-bold text-gray-800">Viajes por el Boyacá</h2>
                <div class="flex space-x-4">
                    <select id="viajes-categoria-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Todas las categorías</option>
                        <!-- Opciones se cargarán dinámicamente -->
                    </select>
                </div>
            </div>
            <div id="viajes-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Actividades de administradores se cargarán aquí -->
            </div>
        </section>

        <!-- Actividades de Empresa (oculto inicialmente) -->
        <section id="actividades-empresa-section" class="mb-16 hidden">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <button onclick="volverAEmpresas()" class="flex items-center text-blue-600 hover:text-blue-800 mb-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Volver a Empresas
                    </button>
                    <h2 id="empresa-title" class="text-3xl font-bold text-gray-800">Actividades de [Empresa]</h2>
                </div>
                <div class="flex space-x-4">
                    <select id="actividad-categoria-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Todas las categorías</option>
                    </select>
                </div>
            </div>
            <div id="actividades-empresa-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Actividades se cargarán aquí -->
            </div>
        </section>

        <!-- Sección de Reservas (oculta por defecto) -->
        <section id="reservas-section" class="mb-16 hidden">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Mis Reservas</h2>
                <button onclick="volverAInicio()" class="text-blue-600 hover:text-blue-800">Volver al inicio</button>
            </div>
            <div id="reservas-list" class="space-y-6">
                <!-- Reservas se cargarán aquí -->
            </div>
        </section>
    </main>

    <!-- Modal de Reserva -->
    <div id="reserva-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800" id="modal-title">Reservar Actividad</h3>
                    <button onclick="closeReservaModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div id="actividad-detalles" class="mb-6">
                    <!-- Detalles de la actividad se cargarán aquí -->
                </div>

                <form id="reserva-form" class="space-y-6">
                    <input type="hidden" id="actividad-id" name="idActividad">
                    <input type="hidden" id="usuario-id" name="idUsuario">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Número de Personas</label>
                            <input type="number" id="numero-personas" name="Numero_Personas" min="1" max="10"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                            <select id="estado-reserva" name="Estado"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="pendiente">Pendiente</option>
                                <option value="confirmada">Confirmada</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notas especiales (opcional)</label>
                        <textarea id="notas-reserva" name="notas" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                  placeholder="¿Alguna petición especial o información adicional?"></textarea>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="closeReservaModal()"
                                class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition duration-300">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition duration-300 transform hover:scale-105">
                            Confirmar Reserva
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Reseñas -->
    <div id="reviews-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">Reseñas de la Actividad</h3>
                    <button onclick="closeReviewsModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="reviews-content">
                    <!-- Reseñas se cargarán aquí -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // ==================== CONFIGURACIÓN INICIAL ====================
        @if(session('jwt_token'))
            localStorage.setItem('token', '{{ session("jwt_token") }}');
            localStorage.setItem('user_role', 'turista');
        @endif

        let currentUser = null;
        let currentEmpresa = null;

        // Función global para cargar reservas
        window.loadReservations = function() {
            const listSection = document.getElementById('reservas-list');

            fetchWithAuth('http://127.0.0.1:8000/api/turista/reservas')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let html = '<h3 class="text-2xl font-bold mb-6 text-gray-800">Mis Reservas</h3>';

                    // Reservas próximas
                    if (data.reservas.proximas.length > 0) {
                        html += '<h4 class="text-xl font-semibold mb-4 text-blue-600">Próximas</h4>';
                        html += '<div class="space-y-4 mb-8">';
                        data.reservas.proximas.forEach(reserva => {
                            html += createReservaHTML(reserva);
                        });
                        html += '</div>';
                    }

                    // Reservas pasadas
                    if (data.reservas.pasadas.length > 0) {
                        html += '<h4 class="text-xl font-semibold mb-4 text-green-600">Pasadas</h4>';
                        html += '<div class="space-y-4">';
                        data.reservas.pasadas.forEach(reserva => {
                            html += createReservaHTML(reserva);
                        });
                        html += '</div>';
                    }

                    if (data.reservas.pasadas.length === 0 && data.reservas.proximas.length === 0) {
                        html += '<p class="text-gray-600">No tienes reservas.</p>';
                    }

                    listSection.innerHTML = html;
                }
            })
            .catch(error => console.error('Error cargando reservas:', error));
        };

        // ==================== UTILIDADES ====================
        function showLoading(element, message = 'Cargando...') {
            element.innerHTML = `<div class="flex items-center justify-center py-12"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div><p class="ml-4 text-gray-600">${message}</p></div>`;
        }

        function showError(element, message = 'Error al cargar') {
            element.innerHTML = `<div class="text-center py-12"><div class="text-red-500 text-6xl mb-4">⚠️</div><p class="text-red-600 text-lg">${message}</p></div>`;
        }

        function fetchWithAuth(url, options = {}) {
            const token = localStorage.getItem('token');
            if (!token) {
                window.location.href = '{{ route("login") }}';
                return Promise.reject(new Error('No token found'));
            }

            const headers = {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json',
                ...options.headers
            };

            return fetch(url, { ...options, headers })
                .then(response => {
                    if (response.status === 401) {
                        localStorage.removeItem('token');
                        window.location.href = '{{ route("login") }}';
                        throw new Error('Unauthorized');
                    }
                    return response;
                });
        }
        function fetchWithAuth(url, options = {}) {
            const token = localStorage.getItem('token');
            if (!token) {
                alert('Tu sesión ha expirado. Por favor, inicia sesión nuevamente.');
                window.location.href = '{{ route("login") }}';
                return Promise.reject(new Error('No token found'));
            }

            const headers = {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json',
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

        // ==================== NAVEGACIÓN ====================
        function scrollToEmpresas() {
            document.getElementById('empresas-section').scrollIntoView({ behavior: 'smooth' });
        }

        function showSection(section) {
            // Ocultar todas las secciones
            document.getElementById('empresas-section').classList.add('hidden');
            document.getElementById('actividades-empresa-section').classList.add('hidden');
            document.getElementById('reservas-section').classList.add('hidden');

            // Mostrar sección seleccionada
            const targetSection = document.getElementById(section + '-section');
            if (targetSection) {
                targetSection.classList.remove('hidden');
                targetSection.scrollIntoView({ behavior: 'smooth' });
            }
        }

        function volverAEmpresas() {
            currentEmpresa = null;
            showSection('empresas');
        }

        function volverAInicio() {
            showSection('empresas');
        }

        // ==================== INICIALIZACIÓN ====================
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM cargado, inicializando dashboard...');

            const token = localStorage.getItem('token');
            console.log('Token encontrado:', !!token);

            if (!token) {
                console.warn('No hay token, redirigiendo al login');
                window.location.href = '{{ route("login") }}';
                return;
            }

            console.log('Iniciando carga de datos...');
            loadUserData();
            loadStats();
            loadEmpresas();
            loadViajes();
            loadCategorias();

            // Agregar event listeners después de cargar los elementos
            setTimeout(() => {
                const categoriaFilter = document.getElementById('categoria-filter');
                const actividadCategoriaFilter = document.getElementById('actividad-categoria-filter');

                if (categoriaFilter) {
                    categoriaFilter.addEventListener('change', filterEmpresas);
                    console.log('Event listener agregado a categoria-filter');
                } else {
                    console.warn('Elemento categoria-filter no encontrado');
                }

                if (actividadCategoriaFilter) {
                    actividadCategoriaFilter.addEventListener('change', filterActividades);
                    console.log('Event listener agregado a actividad-categoria-filter');
                } else {
                    console.warn('Elemento actividad-categoria-filter no encontrado');
                }

                const viajesCategoriaFilter = document.getElementById('viajes-categoria-filter');
                if (viajesCategoriaFilter) {
                    viajesCategoriaFilter.addEventListener('change', filterViajes);
                    console.log('Event listener agregado a viajes-categoria-filter');
                } else {
                    console.warn('Elemento viajes-categoria-filter no encontrado');
                }
            }, 1000); // Esperar 1 segundo para que se carguen los elementos
        });

        function loadUserData() {
            const token = localStorage.getItem('token');
            if (!token) {
                console.warn('No hay token JWT, redirigiendo al login');
                window.location.href = '{{ route("login") }}';
                return;
            }

            fetch('http://127.0.0.1:8000/api/me', {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                console.log('Respuesta del endpoint /api/me:', response.status, response.statusText);
                if (response.status === 401) {
                    console.warn('Token inválido o expirado, redirigiendo al login');
                    localStorage.removeItem('token');
                    window.location.href = '{{ route("login") }}';
                    throw new Error('Token inválido');
                }
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Datos del usuario recibidos:', data);
                if (data.success && data.usuario) {
                    currentUser = data.usuario;
                    // Actualizar nombre en header si existe
                    const userNameElement = document.getElementById('user-name');
                    if (userNameElement) {
                        userNameElement.textContent = data.usuario.Nombre || data.usuario.name;
                    }
                    console.log('Datos del usuario cargados correctamente');
                } else {
                    console.error('Respuesta del servidor no contiene datos válidos del usuario:', data);
                    throw new Error('Datos del usuario inválidos');
                }
            })
            .catch(error => {
                console.error('Error cargando datos del usuario:', error);
                alert('Error al cargar los datos del usuario. Serás redirigido al login.');
                localStorage.removeItem('token');
                window.location.href = '{{ route("login") }}';
            });
        }

        function loadStats() {
            console.log('Iniciando loadStats...');
            const statsSection = document.getElementById('stats-section');
            console.log('Elemento stats-section encontrado:', !!statsSection);

            if (!statsSection) {
                console.error('Elemento stats-section no encontrado');
                return;
            }

            // Usar la nueva API de turista para obtener reservas
            console.log('Haciendo fetch a /api/turista/reservas...');
            fetchWithAuth('http://127.0.0.1:8000/api/turista/reservas')
            .then(response => {
                console.log('Respuesta de stats:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Datos de stats recibidos:', data);
                if (data.success) {
                    const totalReservas = data.reservas.pasadas.length + data.reservas.proximas.length;
                    const reservasConfirmadas = [...data.reservas.pasadas, ...data.reservas.proximas]
                        .filter(r => r.Estado === 'confirmada').length;

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
                    console.log('Estadísticas cargadas correctamente');
                } else {
                    console.error('Respuesta de stats no exitosa:', data);
                }
            })
            .catch(error => {
                console.error('Error cargando estadísticas:', error);
                // Mostrar estadísticas por defecto
                statsSection.innerHTML = `
                    <div class="bg-blue-50 p-6 rounded-lg text-center">
                        <h3 class="text-2xl font-bold text-blue-600">0</h3>
                        <p class="text-gray-600">Total Reservas</p>
                    </div>
                    <div class="bg-green-50 p-6 rounded-lg text-center">
                        <h3 class="text-2xl font-bold text-green-600">0</h3>
                        <p class="text-gray-600">Reservas Confirmadas</p>
                    </div>
                    <div class="bg-yellow-50 p-6 rounded-lg text-center">
                        <h3 class="text-2xl font-bold text-yellow-600">0</h3>
                        <p class="text-gray-600">Reservas Pendientes</p>
                    </div>
                `;
            });
        }

        // ==================== EMPRESAS ====================
        function loadEmpresas() {
            console.log('Iniciando loadEmpresas...');
            const empresasList = document.getElementById('empresas-list');
            console.log('Elemento empresas-list encontrado:', !!empresasList);

            if (!empresasList) {
                console.error('Elemento empresas-list no encontrado');
                return;
            }

            showLoading(empresasList, 'Cargando empresas...');

            console.log('Haciendo fetch a /api/listarEmpresas...');
            fetch('http://127.0.0.1:8000/api/listarEmpresas')
                .then(response => {
                    console.log('Respuesta de empresas:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Datos de empresas recibidos:', data);
                    let empresas = [];
                    if (Array.isArray(data)) {
                        empresas = data;
                    } else if (data.data && Array.isArray(data.data)) {
                        empresas = data.data;
                    } else if (data.success && Array.isArray(data.data)) {
                        empresas = data.data;
                    } else {
                        console.error('Formato de datos inesperado:', data);
                        showError(empresasList, 'Error al cargar empresas');
                        return;
                    }
                    console.log('Empresas a renderizar:', empresas.length);
                    renderEmpresas(empresas, empresasList);
                })
                .catch(error => {
                    console.error('Error cargando empresas:', error);
                    showError(empresasList, 'Error al cargar empresas');
                });
        }

        function renderEmpresas(empresas, container) {
            console.log('Renderizando empresas:', empresas.length);
            container.innerHTML = '';

            if (empresas.length === 0) {
                container.innerHTML = '<div class="col-span-full text-center py-12"><p class="text-gray-500 text-lg">No hay empresas disponibles</p></div>';
                console.log('No hay empresas para mostrar');
                return;
            }

            empresas.forEach((empresa, index) => {
                console.log(`Creando card para empresa ${index + 1}:`, empresa.nombre);
                const empresaCard = createEmpresaCard(empresa);
                container.appendChild(empresaCard);
            });
            console.log('Empresas renderizadas correctamente');
        }

        function createEmpresaCard(empresa) {
            const div = document.createElement('div');
            div.className = 'bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 transform hover:-translate-y-2 cursor-pointer group';
            div.onclick = () => loadEmpresaActividades(empresa.id, empresa.nombre);

            div.innerHTML = `
                <div class="relative h-48 overflow-hidden">
                    <img src="${empresa.logo || 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'}"
                         alt="${empresa.nombre}"
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60"></div>
                    <div class="absolute bottom-4 left-4 right-4">
                        <h3 class="text-xl font-bold text-white mb-1">${empresa.nombre}</h3>
                        <p class="text-white text-sm opacity-90">${empresa.direccion || 'Ubicación no especificada'}</p>
                    </div>
                    <div class="absolute top-4 right-4">
                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold">Activa</span>
                    </div>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">${empresa.descripcion || 'Empresa turística especializada en experiencias únicas en Boyacá.'}</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex text-yellow-400 mr-2">
                                ★★★★☆
                            </div>
                            <span class="text-sm text-gray-500">(4.5)</span>
                        </div>
                        <span class="text-blue-600 font-semibold text-sm group-hover:text-blue-800 transition">Ver actividades →</span>
                    </div>
                </div>
            `;

            return div;
        }

        function loadEmpresaActividades(empresaId, empresaNombre) {
            currentEmpresa = { id: empresaId, nombre: empresaNombre };

            document.getElementById('empresa-title').textContent = `Actividades de ${empresaNombre}`;
            showSection('actividades-empresa');

            const actividadesList = document.getElementById('actividades-empresa-list');
            showLoading(actividadesList, 'Cargando actividades...');

            fetch(`http://127.0.0.1:8000/api/listarActividades?empresa=${empresaId}`)
                .then(response => response.json())
                .then(data => {
                    renderActividades(data, actividadesList);
                })
                .catch(error => {
                    console.error('Error cargando actividades:', error);
                    showError(actividadesList, 'Error al cargar actividades');
                });
        }

        function renderActividades(actividades, container) {
            container.innerHTML = '';

            if (actividades.length === 0) {
                container.innerHTML = '<div class="col-span-full text-center py-12"><p class="text-gray-500 text-lg">Esta empresa no tiene actividades disponibles</p></div>';
                return;
            }

            actividades.forEach(actividad => {
                const actividadCard = createActividadCard(actividad);
                container.appendChild(actividadCard);
            });
        }

        function createActividadCard(actividad) {
            const div = document.createElement('div');
            div.className = 'bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 group';

            div.innerHTML = `
                <div class="relative overflow-hidden rounded-t-2xl">
                    <img src="${actividad.Imagen || 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'}"
                         alt="${actividad.Nombre_Actividad}"
                         class="w-full h-56 object-cover transition-transform duration-500 group-hover:scale-110"
                         onerror="this.src='https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'">
                    <div class="absolute top-4 right-4 bg-white bg-opacity-90 backdrop-blur-sm rounded-full px-3 py-1 shadow-lg">
                        <span class="text-sm font-bold text-gray-800">$${actividad.Precio}</span>
                    </div>
                    <div class="absolute bottom-4 left-4 right-4">
                        <div class="bg-gradient-to-t from-black to-transparent rounded-lg p-4">
                            <h3 class="text-xl font-bold text-white mb-1 line-clamp-2">${actividad.Nombre_Actividad}</h3>
                            <div class="flex items-center text-white text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                ${actividad.Ubicacion}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">${actividad.Descripcion}</p>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <div>
                                <div class="font-medium">${new Date(actividad.Fecha_Actividad).toLocaleDateString('es-ES')}</div>
                                <div class="text-xs">${actividad.Hora_Actividad.substring(0, 5)}</div>
                            </div>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <div>
                                <div class="font-medium">Máx. ${actividad.Cupo_Maximo}</div>
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
                        <button onclick="openReservaModal(${actividad.id}, '${actividad.Nombre_Actividad}')"
                                class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
                            Reservar Ahora
                        </button>
                        <button onclick="openReviewsModal(${actividad.id})"
                                class="px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `;

            return div;
        }

        // ==================== CATEGORÍAS ====================
        function loadCategorias() {
            console.log('Iniciando loadCategorias...');
            fetch('http://127.0.0.1:8000/api/categories/active')
                .then(response => {
                    console.log('Respuesta de categorías:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Datos de categorías recibidos:', data);
                    populateCategoriaFilters(data);
                })
                .catch(error => console.error('Error cargando categorías:', error));
        }

        function populateCategoriaFilters(categorias) {
            console.log('Poblando filtros de categorías con:', categorias);
            const empresaFilter = document.getElementById('categoria-filter');
            const actividadFilter = document.getElementById('actividad-categoria-filter');
            const viajesFilter = document.getElementById('viajes-categoria-filter');

            console.log('Elemento categoria-filter encontrado:', !!empresaFilter);
            console.log('Elemento actividad-categoria-filter encontrado:', !!actividadFilter);
            console.log('Elemento viajes-categoria-filter encontrado:', !!viajesFilter);

            if (!empresaFilter || !actividadFilter || !viajesFilter) {
                console.error('Elementos de filtro no encontrados');
                return;
            }

            // Limpiar opciones existentes excepto "Todas las categorías"
            empresaFilter.innerHTML = '<option value="">Todas las categorías</option>';
            actividadFilter.innerHTML = '<option value="">Todas las categorías</option>';
            viajesFilter.innerHTML = '<option value="">Todas las categorías</option>';

            if (Array.isArray(categorias)) {
                categorias.forEach(categoria => {
                    const option = `<option value="${categoria.id}">${categoria.nombre}</option>`;
                    empresaFilter.innerHTML += option;
                    actividadFilter.innerHTML += option;
                    viajesFilter.innerHTML += option;
                });
                console.log('Filtros de categorías poblados correctamente');
            } else {
                console.error('Categorías no es un array:', categorias);
            }
        }

        function filterEmpresas() {
            const categoriaId = document.getElementById('categoria-filter').value;
            const empresasList = document.getElementById('empresas-list');

            if (!categoriaId) {
                loadEmpresas();
                return;
            }

            showLoading(empresasList, 'Filtrando empresas...');

            fetch(`http://127.0.0.1:8000/api/listarEmpresas?categoria=${categoriaId}`)
                .then(response => response.json())
                .then(data => {
                    let empresas = [];
                    if (Array.isArray(data)) {
                        empresas = data;
                    } else if (data.data && Array.isArray(data.data)) {
                        empresas = data.data;
                    } else if (data.success && Array.isArray(data.data)) {
                        empresas = data.data;
                    } else {
                        console.error('Formato de datos inesperado:', data);
                        showError(empresasList, 'Error al cargar empresas filtradas');
                        return;
                    }
                    renderEmpresas(empresas, empresasList);
                })
                .catch(error => {
                    console.error('Error filtrando empresas:', error);
                    showError(empresasList, 'Error al filtrar empresas');
                });
        }

        function filterActividades() {
            if (!currentEmpresa) return;

            const categoriaId = document.getElementById('actividad-categoria-filter').value;
            const actividadesList = document.getElementById('actividades-empresa-list');
            showLoading(actividadesList, 'Filtrando actividades...');

            let url = `http://127.0.0.1:8000/api/listarActividades?empresa=${currentEmpresa.id}`;
            if (categoriaId) {
                url += `&categoria=${categoriaId}`;
            }

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    renderActividades(data, actividadesList);
                })
                .catch(error => {
                    console.error('Error filtrando actividades:', error);
                    showError(actividadesList, 'Error al filtrar actividades');
                });
        }

        // ==================== VIAJES POR EL BOYACÁ ====================
        function loadViajes() {
            console.log('Iniciando loadViajes...');
            const viajesList = document.getElementById('viajes-list');
            console.log('Elemento viajes-list encontrado:', !!viajesList);

            if (!viajesList) {
                console.error('Elemento viajes-list no encontrado');
                return;
            }

            showLoading(viajesList, 'Cargando viajes...');

            console.log('Haciendo fetch a /api/listarActividades?admin=1...');
            fetch('http://127.0.0.1:8000/api/listarActividades?admin=1')
                .then(response => {
                    console.log('Respuesta de viajes:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Datos de viajes recibidos:', data);
                    renderViajes(data, viajesList);
                })
                .catch(error => {
                    console.error('Error cargando viajes:', error);
                    showError(viajesList, 'Error al cargar viajes');
                });
        }

        function renderViajes(viajes, container) {
            console.log('Renderizando viajes:', viajes.length);
            container.innerHTML = '';

            if (viajes.length === 0) {
                container.innerHTML = '<div class="col-span-full text-center py-12"><p class="text-gray-500 text-lg">No hay viajes disponibles por el momento</p></div>';
                console.log('No hay viajes para mostrar');
                return;
            }

            viajes.forEach(viaje => {
                const viajeCard = createViajeCard(viaje);
                container.appendChild(viajeCard);
            });
            console.log('Viajes renderizados correctamente');
        }

        function createViajeCard(viaje) {
            const div = document.createElement('div');
            div.className = 'bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 group';

            div.innerHTML = `
                <div class="relative overflow-hidden rounded-t-2xl">
                    <img src="${viaje.Imagen || 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'}"
                          alt="${viaje.Nombre_Actividad}"
                          class="w-full h-56 object-cover transition-transform duration-500 group-hover:scale-110"
                          onerror="this.src='https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'">
                    <div class="absolute top-4 right-4 bg-white bg-opacity-90 backdrop-blur-sm rounded-full px-3 py-1 shadow-lg">
                        <span class="text-sm font-bold text-gray-800">$${viaje.Precio}</span>
                    </div>
                    <div class="absolute bottom-4 left-4 right-4">
                        <div class="bg-gradient-to-t from-black to-transparent rounded-lg p-4">
                            <h3 class="text-xl font-bold text-white mb-1 line-clamp-2">${viaje.Nombre_Actividad}</h3>
                            <div class="flex items-center text-white text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                ${viaje.Ubicacion}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">${viaje.Descripcion}</p>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <div>
                                <div class="font-medium">${new Date(viaje.Fecha_Actividad).toLocaleDateString('es-ES')}</div>
                                <div class="text-xs">${viaje.Hora_Actividad.substring(0, 5)}</div>
                            </div>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <div>
                                <div class="font-medium">Máx. ${viaje.Cupo_Maximo}</div>
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
                        <button onclick="openReservaModal(${viaje.id}, '${viaje.Nombre_Actividad}')"
                                class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
                            Reservar Ahora
                        </button>
                        <button onclick="openReviewsModal(${viaje.id})"
                                class="px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `;

            return div;
        }

        function filterViajes() {
            const categoriaId = document.getElementById('viajes-categoria-filter').value;
            const viajesList = document.getElementById('viajes-list');
            showLoading(viajesList, 'Filtrando viajes...');

            let url = 'http://127.0.0.1:8000/api/listarActividades?admin=1';
            if (categoriaId) {
                url += `&categoria=${categoriaId}`;
            }

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    renderViajes(data, viajesList);
                })
                .catch(error => {
                    console.error('Error filtrando viajes:', error);
                    showError(viajesList, 'Error al filtrar viajes');
                });
        }

        // ==================== MODALES ====================
        function openReservaModal(actividadId, actividadName) {
            if (!currentUser) {
                alert('Error: Usuario no identificado');
                return;
            }

            document.getElementById('modal-title').textContent = `Reservar: ${actividadName}`;
            document.getElementById('actividad-id').value = actividadId;
            document.getElementById('usuario-id').value = currentUser.id;

            // Cargar detalles de la actividad
            fetch(`http://127.0.0.1:8000/api/actividades/${actividadId}`)
                .then(response => response.json())
                .then(data => {
                    const detalles = document.getElementById('actividad-detalles');
                    const fecha = new Date(data.Fecha_Actividad).toLocaleDateString('es-ES');
                    const hora = data.Hora_Actividad.substring(0, 5);

                    detalles.innerHTML = `
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div><strong class="text-gray-700">📅 Fecha:</strong> ${fecha}</div>
                                <div><strong class="text-gray-700">🕐 Hora:</strong> ${hora}</div>
                                <div><strong class="text-gray-700">📍 Lugar:</strong> ${data.Ubicacion}</div>
                                <div><strong class="text-gray-700">💰 Precio:</strong> $${data.Precio} por persona</div>
                                <div class="col-span-2"><strong class="text-gray-700">👥 Cupo máximo:</strong> ${data.Cupo_Maximo} personas</div>
                            </div>
                        </div>
                    `;

                    document.getElementById('reserva-modal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error cargando detalles de actividad:', error);
                    alert('Error al cargar detalles de la actividad');
                });
        }

        function closeReservaModal() {
            document.getElementById('reserva-modal').classList.add('hidden');
        }

        function openReviewsModal(actividadId) {
            const reviewsContent = document.getElementById('reviews-content');
            reviewsContent.innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div><p class="mt-2 text-gray-600">Cargando reseñas...</p></div>';

            document.getElementById('reviews-modal').classList.remove('hidden');

            fetch(`http://127.0.0.1:8000/api/comentarios-reservas?actividad_id=${actividadId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        reviewsContent.innerHTML = '<div class="text-center py-8"><p class="text-gray-500">No hay reseñas disponibles para esta actividad</p></div>';
                    } else {
                        reviewsContent.innerHTML = data.map(review => `
                            <div class="border-b border-gray-200 pb-4 mb-4 last:border-b-0 last:pb-0 last:mb-0">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                            ${review.usuario?.name?.charAt(0)?.toUpperCase() || 'U'}
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-800">${review.usuario?.name || 'Usuario Anónimo'}</h4>
                                            <div class="flex text-yellow-400">
                                                ${'★'.repeat(review.calificacion)}${'☆'.repeat(5-review.calificacion)}
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-sm text-gray-500">${new Date(review.fecha_comentario).toLocaleDateString('es-ES')}</span>
                                </div>
                                <p class="text-gray-700 mb-2">${review.comentario}</p>
                                ${review.fotos && review.fotos.length > 0 ? `
                                    <div class="flex gap-2 mt-2">
                                        ${review.fotos.map(foto => `
                                            <img src="/storage/${foto.ruta_imagen}" alt="${foto.titulo || 'Foto de reseña'}"
                                                 class="w-16 h-16 object-cover rounded-lg cursor-pointer hover:opacity-80 transition"
                                                 onclick="openImageModal('/storage/${foto.ruta_imagen}')">
                                        `).join('')}
                                    </div>
                                ` : ''}
                            </div>
                        `).join('');
                    }
                })
                .catch(error => {
                    console.error('Error cargando reseñas:', error);
                    reviewsContent.innerHTML = '<div class="text-center py-8"><p class="text-red-500">Error al cargar las reseñas</p></div>';
                });
        }

        function closeReviewsModal() {
            document.getElementById('reviews-modal').classList.add('hidden');
        }

        function openImageModal(imageSrc) {
            // Crear modal para imagen ampliada
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4';
            modal.innerHTML = `
                <div class="relative max-w-4xl max-h-full">
                    <img src="${imageSrc}" alt="Imagen ampliada" class="max-w-full max-h-full object-contain">
                    <button onclick="this.parentElement.parentElement.remove()" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300">
                        ×
                    </button>
                </div>
            `;
            document.body.appendChild(modal);
        }

        // ==================== RESERVAS ====================
        function handleReservaSubmit(e) {
            e.preventDefault();

            // Mostrar indicador de carga
            const submitButton = e.target.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mx-auto"></div>';

            const formData = new FormData(e.target);
            const data = {
                idUsuario: parseInt(formData.get('idUsuario')),
                idActividad: parseInt(formData.get('idActividad')),
                Numero_Personas: parseInt(formData.get('Numero_Personas')),
                Estado: formData.get('Estado'),
                notas: document.getElementById('notas-reserva').value.trim() || null
            };

            console.log('Enviando datos de reserva:', data);

            fetchWithAuth('http://127.0.0.1:8000/api/crearReservas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    return response.json().then(err => { throw err; });
                }
            })
            .then(data => {
                if (data.success && data.reserva) {
                    // Mostrar notificación de éxito
                    showNotification('¡Reserva creada exitosamente! Revisa tu email para la confirmación.', 'success');
                    closeReservaModal();
                    e.target.reset();
                    // Recargar estadísticas y reservas
                    loadStats();
                    // Si estamos en la sección de reservas, actualizar la lista
                    if (!document.getElementById('reservas-section').classList.contains('hidden')) {
                        window.loadReservations();
                    }
                } else {
                    throw new Error(data.message || 'Error desconocido');
                }
            })
            .catch(error => {
                console.error('Error creando reserva:', error);
                let errorMessage = 'Error al crear la reserva';

                if (error.errors) {
                    errorMessage = Object.values(error.errors).flat().join('\n');
                } else if (error.message) {
                    errorMessage = error.message;
                }

                showNotification(errorMessage, 'error');
            })
            .finally(() => {
                // Restaurar botón
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            });
        }

        // Función para mostrar notificaciones
        function showNotification(message, type = 'info') {
            // Crear elemento de notificación
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;

            const colors = {
                success: 'bg-green-500 text-white',
                error: 'bg-red-500 text-white',
                warning: 'bg-yellow-500 text-black',
                info: 'bg-blue-500 text-white'
            };

            notification.classList.add(...colors[type].split(' '));
            notification.innerHTML = `
                <div class="flex items-center">
                    <span class="flex-1">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-xl">&times;</button>
                </div>
            `;

            document.body.appendChild(notification);

            // Animar entrada
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Auto-remover después de 5 segundos
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.classList.add('translate-x-full');
                    setTimeout(() => notification.remove(), 300);
                }
            }, 5000);
        }


        function createReservaHTML(reserva) {
            const estadoClass = reserva.Estado === 'confirmada' ? 'bg-green-100 text-green-800' :
                              reserva.Estado === 'cancelada' ? 'bg-red-100 text-red-800' :
                              'bg-yellow-100 text-yellow-800';

            const fechaActividad = new Date(reserva.Fecha_Reserva + ' ' + (reserva.hora || '00:00'));
            const ahora = new Date();
            const esPasada = fechaActividad < ahora;
            const puedeEditar = reserva.Estado === 'pendiente' && !esPasada;
            const puedeCancelar = (reserva.Estado === 'pendiente' || reserva.Estado === 'confirmada') && !esPasada;

            return `
                <div class="border rounded-lg p-6 hover:shadow-md transition duration-300">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h4 class="font-bold text-lg text-gray-800">Reserva #${reserva.id}</h4>
                            <span class="px-3 py-1 rounded-full text-sm font-medium ${estadoClass}">${reserva.Estado.charAt(0).toUpperCase() + reserva.Estado.slice(1)}</span>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-500">${new Date(reserva.created_at).toLocaleDateString('es-ES')}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <h5 class="font-semibold text-gray-800 mb-2">${reserva.actividad?.Nombre_Actividad || 'Actividad no disponible'}</h5>
                            <div class="space-y-1 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    ${new Date(reserva.Fecha_Reserva).toLocaleDateString('es-ES')} ${reserva.hora ? 'a las ' + reserva.hora.substring(0, 5) : ''}
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    ${reserva.Numero_Personas} persona${reserva.Numero_Personas !== 1 ? 's' : ''}
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    $${reserva.actividad ? reserva.actividad.Precio * reserva.Numero_Personas : 'N/A'}
                                </div>
                            </div>
                        </div>

                        <div>
                            <h6 class="font-medium text-gray-800 mb-2">Empresa</h6>
                            <p class="text-sm text-gray-600">${reserva.actividad?.empresa?.nombre || 'Empresa no disponible'}</p>

                            ${reserva.notas ? `
                                <h6 class="font-medium text-gray-800 mb-1 mt-3">Notas</h6>
                                <p class="text-sm text-gray-600 italic">${reserva.notas}</p>
                            ` : ''}
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2 pt-4 border-t border-gray-200">
                        <button onclick="verComentarios(${reserva.id})"
                                class="px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition duration-200 text-sm font-medium">
                            Ver Reseñas (${reserva.comentarios?.length || 0})
                        </button>

                        ${esPasada ? `
                            <button onclick="agregarResena(${reserva.id})"
                                    class="px-3 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition duration-200 text-sm font-medium">
                                Agregar Reseña
                            </button>
                        ` : ''}

                        ${puedeEditar ? `
                            <button onclick="editarReserva(${reserva.id})"
                                    class="px-3 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition duration-200 text-sm font-medium">
                                Editar
                            </button>
                        ` : ''}

                        ${puedeCancelar ? `
                            <button onclick="cancelarReserva(${reserva.id})"
                                    class="px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition duration-200 text-sm font-medium">
                                Cancelar
                            </button>
                        ` : ''}

                        <button onclick="verDetallesReserva(${reserva.id})"
                                class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition duration-200 text-sm font-medium">
                            Ver Detalles
                        </button>
                    </div>
                </div>
            `;
        }

        function showSection(section) {
            // Ocultar todas las secciones existentes
            const empresasSection = document.getElementById('empresas-section');
            const actividadesEmpresaSection = document.getElementById('actividades-empresa-section');
            const reservasSection = document.getElementById('reservas-section');

            if (empresasSection) empresasSection.classList.add('hidden');
            if (actividadesEmpresaSection) actividadesEmpresaSection.classList.add('hidden');
            if (reservasSection) reservasSection.classList.add('hidden');

            // Mostrar la sección seleccionada
            const selectedSection = document.getElementById(section + '-section');
            if (selectedSection) {
                selectedSection.classList.remove('hidden');
                // Scroll suave a la sección
                selectedSection.scrollIntoView({ behavior: 'smooth' });
            }

            // Cargar contenido según la sección
            switch(section) {
                case 'empresas':
                    // Las empresas ya están cargadas inicialmente
                    break;
                case 'actividades-empresa':
                    // Las actividades se cargan al hacer clic en una empresa
                    break;
                case 'reservas':
                    loadReservations();
                    break;
            }
        }

        function loadPerfil() {
            const perfilContent = document.getElementById('perfil-content');

            fetchWithAuth('http://127.0.0.1:8000/api/turista/perfil')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const perfil = data.perfil;
                    perfilContent.innerHTML = `
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <h4 class="text-lg font-semibold mb-4">Información Personal</h4>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Foto de Perfil</label>
                                        <div class="mt-1 flex items-center">
                                            ${perfil.foto_perfil ?
                                                `<img src="${perfil.foto_perfil}" alt="Foto de perfil" class="w-20 h-20 rounded-full object-cover">` :
                                                `<div class="w-20 h-20 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-bold text-xl">${perfil.name ? perfil.name.charAt(0).toUpperCase() : 'U'}</div>`
                                            }
                                            <input type="file" id="foto-perfil-input" accept="image/*" class="ml-4">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Biografía</label>
                                        <textarea id="biografia-input" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">${perfil.biografia || ''}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Privacidad del Perfil</label>
                                        <select id="privacidad-input" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="publico" ${perfil.privacidad_perfil === 'publico' ? 'selected' : ''}>Público</option>
                                            <option value="privado" ${perfil.privacidad_perfil === 'privado' ? 'selected' : ''}>Privado</option>
                                        </select>
                                    </div>
                                    <button onclick="guardarPerfil()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                        Guardar Cambios
                                    </button>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold mb-4">Estadísticas</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-blue-50 p-4 rounded-lg text-center">
                                        <div class="text-2xl font-bold text-blue-600">${perfil.total_fotos}</div>
                                        <div class="text-sm text-gray-600">Fotos</div>
                                    </div>
                                    <div class="bg-green-50 p-4 rounded-lg text-center">
                                        <div class="text-2xl font-bold text-green-600">${perfil.total_likes_recibidos}</div>
                                        <div class="text-sm text-gray-600">Likes Recibidos</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }
            })
            .catch(error => console.error('Error cargando perfil:', error));
        }

        function guardarPerfil() {
            const formData = new FormData();
            const fotoInput = document.getElementById('foto-perfil-input');
            const biografia = document.getElementById('biografia-input').value;
            const privacidad = document.getElementById('privacidad-input').value;

            if (fotoInput.files[0]) {
                formData.append('foto_perfil', fotoInput.files[0]);
            }
            formData.append('biografia', biografia);
            formData.append('privacidad_perfil', privacidad);

            fetchWithAuth('http://127.0.0.1:8000/api/turista/perfil', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Perfil actualizado exitosamente');
                    loadPerfil(); // Recargar perfil
                } else {
                    alert('Error: ' + (data.message || 'Error desconocido'));
                }
            })
            .catch(error => console.error('Error guardando perfil:', error));
        }

        function loadFotos() {
            const fotosContent = document.getElementById('fotos-content');

            fotosContent.innerHTML = `
                <div class="mb-6">
                    <h4 class="text-lg font-semibold mb-4">Subir Nueva Foto</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Título</label>
                            <input type="text" id="foto-titulo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Imagen</label>
                            <input type="file" id="foto-imagen" accept="image/*" class="mt-1 block w-full">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea id="foto-descripcion" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Privacidad</label>
                        <select id="foto-privacidad" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="publico">Público</option>
                            <option value="privado">Privado</option>
                        </select>
                    </div>
                    <button onclick="subirFoto()" class="mt-4 bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700">
                        Subir Foto
                    </button>
                </div>
                <div id="mis-fotos">
                    <!-- Fotos del usuario se cargarán aquí -->
                </div>
            `;

            loadMisFotos();
        }

        function loadMisFotos() {
            fetchWithAuth('http://127.0.0.1:8000/api/turista/perfil')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Aquí podríamos cargar las fotos del usuario desde el perfil o crear un endpoint específico
                    document.getElementById('mis-fotos').innerHTML = '<p class="text-gray-600">Funcionalidad de fotos próximamente disponible.</p>';
                }
            })
            .catch(error => console.error('Error cargando fotos:', error));
        }

        function subirFoto() {
            const titulo = document.getElementById('foto-titulo').value;
            const descripcion = document.getElementById('foto-descripcion').value;
            const privacidad = document.getElementById('foto-privacidad').value;
            const imagen = document.getElementById('foto-imagen').files[0];

            if (!titulo || !imagen) {
                alert('Por favor completa el título y selecciona una imagen');
                return;
            }

            const formData = new FormData();
            formData.append('titulo', titulo);
            formData.append('descripcion', descripcion);
            formData.append('privacidad', privacidad);
            formData.append('imagen', imagen);

            fetchWithAuth('http://127.0.0.1:8000/api/turista/fotos', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Foto subida exitosamente');
                    loadMisFotos();
                } else {
                    alert('Error: ' + (data.message || 'Error desconocido'));
                }
            })
            .catch(error => console.error('Error subiendo foto:', error));
        }

        function loadFeed() {
            const feedContent = document.getElementById('feed-content');

            fetchWithAuth('http://127.0.0.1:8000/api/turista/feed')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let html = '';
                    if (data.actividades.length === 0) {
                        html = '<p class="text-gray-600">No tienes actividades recientes.</p>';
                    } else {
                        html = '<div class="space-y-4">';
                        data.actividades.forEach(actividad => {
                            html += `
                                <div class="border rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm text-gray-500">${new Date(actividad.fecha).toLocaleDateString()}</span>
                                        <span class="px-2 py-1 rounded text-xs ${actividad.tipo === 'foto_subida' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800'}">
                                            ${actividad.tipo.replace('_', ' ').toUpperCase()}
                                        </span>
                                    </div>
                                    <div class="text-gray-800">
                                        ${actividad.tipo === 'foto_subida' ?
                                            `<strong>${actividad.titulo}</strong> - ${actividad.likes_count} likes` :
                                            actividad.comentario ? `<strong>Comentario:</strong> ${actividad.comentario}` :
                                            'Actividad realizada'
                                        }
                                    </div>
                                </div>
                            `;
                        });
                        html += '</div>';
                    }
                    feedContent.innerHTML = html;
                }
            })
            .catch(error => console.error('Error cargando feed:', error));
        }

        function verComentarios(reservaId) {
            // Mostrar reseñas de la reserva
            fetchWithAuth(`http://127.0.0.1:8000/api/comentarios-reservas?reserva_id=${reservaId}`)
                .then(response => response.json())
                .then(data => {
                    const modal = document.createElement('div');
                    modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
                    let html = `
                        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white max-h-96 overflow-y-auto">
                            <div class="mt-3">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Reseñas de la Reserva</h3>
                    `;
                    if (data.length === 0) {
                        html += '<p class="text-gray-600">No hay reseñas aún.</p>';
                    } else {
                        data.forEach(review => {
                            html += `
                                <div class="border-b pb-4 mb-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <strong>${review.usuario.name}</strong>
                                        <span class="text-yellow-500">${'★'.repeat(review.calificacion)}${'☆'.repeat(5-review.calificacion)}</span>
                                    </div>
                                    <p class="text-gray-700 mb-2">${review.comentario}</p>
                                    <small class="text-gray-500">${new Date(review.fecha_comentario).toLocaleDateString()}</small>
                            `;
                            if (review.fotos && review.fotos.length > 0) {
                                html += '<div class="mt-2 flex gap-2">';
                                review.fotos.forEach(foto => {
                                    html += `<img src="/storage/${foto.ruta_imagen}" alt="${foto.titulo}" class="w-20 h-20 object-cover rounded">`;
                                });
                                html += '</div>';
                            }
                            html += '</div>';
                        });
                    }
                    html += `
                                <div class="flex justify-end mt-4">
                                    <button onclick="closeModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    `;
                    modal.innerHTML = html;
                    document.body.appendChild(modal);

                    window.closeModal = function() {
                        document.body.removeChild(modal);
                    };
                })
                .catch(error => console.error('Error cargando reseñas:', error));
        }

        function editarReserva(reservaId) {
            // Obtener datos de la reserva
            fetchWithAuth(`http://127.0.0.1:8000/api/reservas/${reservaId}`)
            .then(response => response.json())
            .then(reserva => {
                // Abrir modal de edición
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
                modal.innerHTML = `
                    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-lg shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Editar Reserva #${reservaId}</h3>
                            <form id="editar-reserva-form">
                                <input type="hidden" name="id" value="${reservaId}">

                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Número de Personas</label>
                                    <input type="number" name="Numero_Personas" value="${reserva.Numero_Personas}"
                                           min="1" max="10" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Estado</label>
                                    <select name="Estado" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="pendiente" ${reserva.Estado === 'pendiente' ? 'selected' : ''}>Pendiente</option>
                                        <option value="confirmada" ${reserva.Estado === 'confirmada' ? 'selected' : ''}>Confirmada</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Notas (opcional)</label>
                                    <textarea name="notas" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">${reserva.notas || ''}</textarea>
                                </div>

                                <div class="flex items-center justify-between">
                                    <button type="button" onclick="closeModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cancelar</button>
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Guardar Cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                `;
                document.body.appendChild(modal);

                document.getElementById('editar-reserva-form').addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitEditarReserva();
                });

                window.closeModal = function() {
                    document.body.removeChild(modal);
                };

                window.submitEditarReserva = function() {
                    const formData = new FormData(document.getElementById('editar-reserva-form'));
                    const data = Object.fromEntries(formData);

                    fetchWithAuth(`http://127.0.0.1:8000/api/turista/reservas/${reservaId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('Reserva actualizada exitosamente', 'success');
                            closeModal();
                            window.loadReservations();
                            loadStats();
                        } else {
                            showNotification(data.message || 'Error al actualizar reserva', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error editando reserva:', error);
                        showNotification('Error al actualizar la reserva', 'error');
                    });
                };
            })
            .catch(error => {
                console.error('Error obteniendo reserva:', error);
                showNotification('Error al cargar datos de la reserva', 'error');
            });
        }

        function cancelarReserva(reservaId) {
            if (!confirm('¿Estás seguro de que quieres cancelar esta reserva? Esta acción no se puede deshacer.')) {
                return;
            }

            fetchWithAuth(`http://127.0.0.1:8000/api/turista/reservas/${reservaId}/cancelar`, {
                method: 'PUT'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Reserva cancelada exitosamente', 'success');
                    window.loadReservations();
                    loadStats();
                } else {
                    showNotification(data.message || 'Error al cancelar reserva', 'error');
                }
            })
            .catch(error => {
                console.error('Error cancelando reserva:', error);
                showNotification('Error al cancelar la reserva', 'error');
            });
        }

        function verDetallesReserva(reservaId) {
            fetchWithAuth(`http://127.0.0.1:8000/api/reservas/${reservaId}`)
            .then(response => response.json())
            .then(reserva => {
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
                modal.innerHTML = `
                    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-medium text-gray-900">Detalles de Reserva #${reservaId}</h3>
                                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="font-semibold text-gray-800">Información General</h4>
                                        <div class="mt-2 space-y-2 text-sm">
                                            <p><strong>ID:</strong> ${reserva.id}</p>
                                            <p><strong>Estado:</strong>
                                                <span class="px-2 py-1 rounded text-xs ${reserva.Estado === 'confirmada' ? 'bg-green-100 text-green-800' : reserva.Estado === 'cancelada' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'}">
                                                    ${reserva.Estado}
                                                </span>
                                            </p>
                                            <p><strong>Fecha de creación:</strong> ${new Date(reserva.created_at).toLocaleString('es-ES')}</p>
                                            <p><strong>Última actualización:</strong> ${new Date(reserva.updated_at).toLocaleString('es-ES')}</p>
                                        </div>
                                    </div>

                                    <div>
                                        <h4 class="font-semibold text-gray-800">Actividad</h4>
                                        <div class="mt-2 space-y-2 text-sm">
                                            <p><strong>Nombre:</strong> ${reserva.actividad?.Nombre_Actividad || 'N/A'}</p>
                                            <p><strong>Fecha:</strong> ${new Date(reserva.Fecha_Reserva).toLocaleDateString('es-ES')}</p>
                                            <p><strong>Hora:</strong> ${reserva.hora || 'N/A'}</p>
                                            <p><strong>Personas:</strong> ${reserva.Numero_Personas}</p>
                                            <p><strong>Precio total:</strong> $${reserva.actividad ? reserva.actividad.Precio * reserva.Numero_Personas : 'N/A'}</p>
                                        </div>
                                    </div>
                                </div>

                                ${reserva.notas ? `
                                    <div>
                                        <h4 class="font-semibold text-gray-800">Notas Especiales</h4>
                                        <p class="mt-2 text-sm text-gray-600 bg-gray-50 p-3 rounded">${reserva.notas}</p>
                                    </div>
                                ` : ''}

                                <div class="flex justify-end pt-4 border-t">
                                    <button onclick="closeModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                document.body.appendChild(modal);

                window.closeModal = function() {
                    document.body.removeChild(modal);
                };
            })
            .catch(error => {
                console.error('Error obteniendo detalles de reserva:', error);
                showNotification('Error al cargar detalles de la reserva', 'error');
            });
        }

        function agregarResena(reservaId) {
            // Abrir modal para agregar reseña con calificación y fotos
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
            modal.innerHTML = `
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Agregar Reseña</h3>
                        <form id="resena-form">
                            <input type="hidden" name="id_reserva" value="${reservaId}">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Comentario</label>
                                <textarea name="comentario" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Calificación (1-5 estrellas)</label>
                                <select name="calificacion" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                    <option value="1">1 estrella</option>
                                    <option value="2">2 estrellas</option>
                                    <option value="3">3 estrellas</option>
                                    <option value="4">4 estrellas</option>
                                    <option value="5">5 estrellas</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Foto (opcional)</label>
                                <input type="file" name="foto" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            <div class="flex items-center justify-between">
                                <button type="button" onclick="closeModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cancelar</button>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Enviar Reseña</button>
                            </div>
                        </form>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);

            document.getElementById('resena-form').addEventListener('submit', function(e) {
                e.preventDefault();
                submitResena();
            });

            window.closeModal = function() {
                document.body.removeChild(modal);
            };

            window.submitResena = function() {
                const formData = new FormData(document.getElementById('resena-form'));
                const data = Object.fromEntries(formData);

                fetchWithAuth('http://127.0.0.1:8000/api/comentarios-reservas', {
                    method: 'POST',
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.id) {
                        showNotification('Reseña agregada exitosamente', 'success');
                        closeModal();
                        window.loadReservations();
                    } else {
                        showNotification('Error: ' + (data.message || 'Error desconocido'), 'error');
                    }
                })
                .catch(error => console.error('Error agregando reseña:', error));
            };
        }
    </script>
</body>
</html>