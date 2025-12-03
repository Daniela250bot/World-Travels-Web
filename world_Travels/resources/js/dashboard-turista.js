// ==================== CONFIGURACIÓN INICIAL ====================
const jwtToken = document.querySelector('meta[name="jwt-token"]').getAttribute('content');
if (jwtToken) {
    localStorage.setItem('token', jwtToken);
    localStorage.setItem('user_role', 'turista');
}

let currentUser = null;
let currentEmpresa = null;

// ==================== UTILIDADES DE ANIMACIÓN ====================
function fadeIn(element, duration = 300) {
    element.style.opacity = '0';
    element.style.display = 'block';
    element.style.transition = `opacity ${duration}ms ease-in-out`;

    setTimeout(() => {
        element.style.opacity = '1';
    }, 10);
}

function fadeOut(element, duration = 300) {
    element.style.transition = `opacity ${duration}ms ease-in-out`;
    element.style.opacity = '0';

    setTimeout(() => {
        element.style.display = 'none';
    }, duration);
}

function smoothScrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function showLoadingSpinner(element) {
    element.innerHTML = `
        <div class="flex items-center justify-center py-16">
            <div class="animate-spin rounded-full h-16 w-16 border-4 border-blue-200 border-t-blue-600"></div>
            <span class="ml-4 text-gray-600 font-medium">Cargando...</span>
        </div>
    `;
}

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
    loadReservasOverview();
    loadPublicaciones();
    loadPublicacionesPublicas();
    loadEmpresas();
    loadViajes();
    loadCategorias();

    // Inicializar mapa y calendario después de cargar categorías
    setTimeout(() => {
        inicializarMapa();
        inicializarCalendario();
    }, 1500);
});

// Funciones principales de carga de datos
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

    fetchWithAuth('http://127.0.0.1:8000/api/turista/estadisticas')
    .then(response => {
        console.log('Respuesta de stats:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Datos de stats recibidos:', data);
        if (data.success) {
            const estadisticas = data.estadisticas;

            statsSection.innerHTML = `
                <div class="bg-blue-50 p-6 rounded-lg text-center">
                    <h3 class="text-2xl font-bold text-blue-600">${estadisticas.reservas_total || 0}</h3>
                    <p class="text-gray-600">Total Reservas</p>
                </div>
                <div class="bg-green-50 p-6 rounded-lg text-center">
                    <h3 class="text-2xl font-bold text-green-600">${estadisticas.reservas_confirmadas || 0}</h3>
                    <p class="text-gray-600">Reservas Confirmadas</p>
                </div>
                <div class="bg-purple-50 p-6 rounded-lg text-center">
                    <h3 class="text-2xl font-bold text-purple-600">${estadisticas.reservas_proximas || 0}</h3>
                    <p class="text-gray-600">Próximas</p>
                </div>
            `;
            console.log('Estadísticas cargadas correctamente');
        } else {
            console.error('Respuesta de stats no exitosa:', data);
        }
    })
    .catch(error => {
        console.error('Error cargando estadísticas:', error);
        statsSection.innerHTML = `
            <div class="bg-blue-50 p-6 rounded-lg text-center">
                <h3 class="text-2xl font-bold text-blue-600">0</h3>
                <p class="text-gray-600">Total Reservas</p>
            </div>
            <div class="bg-green-50 p-6 rounded-lg text-center">
                <h3 class="text-2xl font-bold text-green-600">0</h3>
                <p class="text-gray-600">Reservas Confirmadas</p>
            </div>
            <div class="bg-purple-50 p-6 rounded-lg text-center">
                <h3 class="text-2xl font-bold text-purple-600">0</h3>
                <p class="text-gray-600">Próximas</p>
            </div>
        `;
    });
}

// Funciones de navegación y secciones
function scrollToEmpresas() {
    document.getElementById('empresas-section').scrollIntoView({ behavior: 'smooth' });
}

function showSection(section) {
    const allSections = ['empresas-section', 'actividades-empresa-section', 'reservas-section', 'perfil-section'];

    allSections.forEach(sectionId => {
        const sectionElement = document.getElementById(sectionId);
        if (sectionElement && !sectionElement.classList.contains('hidden')) {
            fadeOut(sectionElement, 200);
        }
    });

    setTimeout(() => {
        allSections.forEach(sectionId => {
            const sectionElement = document.getElementById(sectionId);
            if (sectionElement) {
                if (sectionId === section + '-section') {
                    fadeIn(sectionElement, 300);
                } else {
                    sectionElement.classList.add('hidden');
                }
            }
        });

        setTimeout(() => {
            const selectedSection = document.getElementById(section + '-section');
            if (selectedSection) {
                smoothScrollToTop();
            }
        }, 100);
    }, 200);

    switch(section) {
        case 'empresas':
            break;
        case 'actividades-empresa':
            break;
        case 'reservas':
            setTimeout(() => loadReservasCompletas(), 300);
            break;
        case 'perfil':
            setTimeout(() => loadPerfil(), 300);
            break;
    }
}

// Funciones de carga de empresas y actividades
function loadEmpresas() {
    console.log('Iniciando loadEmpresas...');
    const empresasList = document.getElementById('empresas-list');
    console.log('Elemento empresas-list encontrado:', !!empresasList);

    if (!empresasList) {
        console.error('Elemento empresas-list no encontrado');
        return;
    }

    showLoadingSpinner(empresasList);

    console.log('Haciendo fetch a /api/listarEmpresas...');
    fetch('http://127.0.0.1:8000/api/listarEmpresas')
        .then(response => {
            console.log('Respuesta de empresas:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
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
            showError(empresasList, 'Error al cargar empresas. Revisa tu conexión a internet.');
        });
}

function renderEmpresas(empresas, container) {
    console.log('Renderizando empresas:', empresas.length);
    container.innerHTML = '';

    if (empresas.length === 0) {
        container.innerHTML = `
            <div class="col-span-full text-center py-16 bg-gradient-to-br from-gray-50 to-blue-50 rounded-2xl">
                <div class="text-gray-400 text-6xl mb-4">🏢</div>
                <h4 class="text-xl font-semibold text-gray-700 mb-2">No hay empresas disponibles</h4>
                <p class="text-gray-600">Estamos trabajando para traerte las mejores experiencias</p>
            </div>
        `;
        console.log('No hay empresas para mostrar');
        return;
    }

    empresas.forEach((empresa, index) => {
        console.log(`Creando card para empresa ${index + 1}:`, empresa.nombre);
        const empresaCard = createEmpresaCard(empresa);
        empresaCard.classList.add('empresa-card');
        empresaCard.style.opacity = '0';
        empresaCard.style.transform = 'translateY(20px)';
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

// Funciones de reservas
function loadReservasOverview() {
    console.log('Cargando vista previa de reservas...');

    const proximasContainer = document.getElementById('reservas-proximas');
    const pasadasContainer = document.getElementById('reservas-pasadas');
    const proximasEmpty = document.getElementById('reservas-proximas-empty');
    const pasadasEmpty = document.getElementById('reservas-pasadas-empty');

    if (!proximasContainer || !pasadasContainer || !proximasEmpty || !pasadasEmpty) {
        console.error('Elementos de reservas no encontrados');
        return;
    }

    fetchWithAuth('http://127.0.0.1:8000/api/turista/reservas')
    .then(response => {
        console.log('Respuesta de reservas overview:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Datos de reservas overview recibidos:', data);
        if (data.success) {
            const proximas = data.reservas.proximas || [];
            console.log('Reservas próximas:', proximas.length);
            if (proximas.length > 0) {
                proximasContainer.innerHTML = '';
                proximas.forEach(reserva => {
                    proximasContainer.appendChild(createReservaCard(reserva, 'proxima'));
                });
                proximasEmpty.classList.add('hidden');
            } else {
                proximasEmpty.classList.remove('hidden');
            }

            const pasadas = data.reservas.pasadas || [];
            console.log('Reservas pasadas:', pasadas.length);
            if (pasadas.length > 0) {
                pasadasContainer.innerHTML = '';
                pasadas.forEach(reserva => {
                    pasadasContainer.appendChild(createReservaCard(reserva, 'pasada'));
                });
                pasadasEmpty.classList.add('hidden');
            } else {
                pasadasEmpty.classList.remove('hidden');
            }
        } else {
            console.error('Respuesta no exitosa:', data);
            proximasEmpty.classList.remove('hidden');
            pasadasEmpty.classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error('Error cargando vista previa de reservas:', error);
        showNotification('Error al cargar las reservas. Revisa la consola para más detalles.', 'error');
        proximasEmpty.classList.remove('hidden');
        pasadasEmpty.classList.remove('hidden');
    });
}

function createReservaCard(reserva, tipo) {
    const div = document.createElement('div');
    div.className = 'bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 cursor-pointer group';
    div.onclick = () => showSection('reservas');

    const estadoClass = reserva.Estado === 'confirmada' ? 'bg-green-100 text-green-800' :
                      reserva.Estado === 'cancelada' ? 'bg-red-100 text-red-800' :
                      'bg-yellow-100 text-yellow-800';

    const tipoClass = tipo === 'proxima' ? 'border-l-4 border-blue-500' : 'border-l-4 border-green-500';

    div.innerHTML = `
        <div class="relative overflow-hidden rounded-t-2xl">
            <img src="${reserva.actividad?.Imagen || 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'}"
                 alt="${reserva.actividad?.Nombre_Actividad || 'Actividad'}"
                 class="w-full h-40 object-cover group-hover:scale-105 transition duration-300">
            <div class="absolute top-3 right-3">
                <span class="px-2 py-1 rounded-full text-xs font-medium ${estadoClass}">${reserva.Estado}</span>
            </div>
        </div>
        <div class="${tipoClass} p-4">
            <h4 class="font-bold text-gray-800 mb-2 line-clamp-1">${reserva.actividad?.Nombre_Actividad || 'Actividad no disponible'}</h4>
            <div class="space-y-1 text-sm text-gray-600">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    ${new Date(reserva.Fecha_Reserva).toLocaleDateString('es-ES')} ${reserva.hora ? 'a las ' + reserva.hora.substring(0, 5) : ''}
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    ${reserva.Numero_Personas} persona${reserva.Numero_Personas !== 1 ? 's' : ''}
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold text-blue-600">$${reserva.actividad ? reserva.actividad.Precio * reserva.Numero_Personas : 'N/A'}</span>
                    <span class="text-sm text-gray-500 group-hover:text-blue-600 transition">Ver detalles →</span>
                </div>
            </div>
        </div>
    `;

    return div;
}

// Funciones de notificaciones
function showNotification(message, type = 'info') {
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

    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    setTimeout(() => {
        if (notification.parentElement) {
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);
}

// Funciones de mapa y calendario (placeholders para implementación futura)
function inicializarMapa() {
    console.log('Mapa inicializado (placeholder)');
}

function inicializarCalendario() {
    console.log('Calendario inicializado (placeholder)');
}

// Funciones de perfil (placeholders para implementación futura)
function loadPerfil() {
    console.log('Perfil cargado (placeholder)');
}

function loadPublicaciones() {
    console.log('Publicaciones cargadas (placeholder)');
}

function loadPublicacionesPublicas() {
    console.log('Publicaciones públicas cargadas (placeholder)');
}

function loadViajes() {
    console.log('Viajes cargados (placeholder)');
}

function loadCategorias() {
    console.log('Categorías cargadas (placeholder)');
}

function loadReservasCompletas() {
    console.log('Reservas completas cargadas (placeholder)');
}

function loadEmpresaActividades(empresaId, empresaNombre) {
    console.log('Actividades de empresa cargadas (placeholder):', empresaId, empresaNombre);
}

// Exportar funciones globales si es necesario
window.showSection = showSection;
window.scrollToEmpresas = scrollToEmpresas;
window.showNotification = showNotification;