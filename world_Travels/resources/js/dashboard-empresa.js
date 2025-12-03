
// Dashboard Empresa - Funciones JavaScript

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
    // Guardar el token JWT en localStorage después del login
    localStorage.setItem('token', '{{ session("jwt_token", "") }}');
    localStorage.setItem('user_role', 'empresa');

    // Inicializar funcionalidades
    loadUserData();
    loadStats();
    loadActivities();

    // Event listeners para formularios
    const createActivityForm = document.getElementById('createActivityForm');
    if (createActivityForm) {
        createActivityForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleCreateActivity();
        });
    }

    const asignarEmpleadoForm = document.getElementById('asignarEmpleadoForm');
    if (asignarEmpleadoForm) {
        asignarEmpleadoForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleAsignarEmpleado();
        });
    }
});

// Funciones de carga de datos
function loadUserData() {
    fetchWithAuth('http://127.0.0.1:8000/api/empresas/me')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('user-name').textContent = data.empresa.nombre;
        }
    })
    .catch(error => {
        console.error('Error cargando datos del usuario:', error);
        // fetchWithAuth ya maneja la redirección en caso de 401
    });
}

function loadStats() {
    const statsSection = document.getElementById('stats-section');

    // Obtener el ID de la empresa
    fetchWithAuth('http://127.0.0.1:8000/api/empresas/me')
    .then(response => response.json())
    .then(userData => {
        if (userData.success && userData.empresa) {
            const empresaId = userData.empresa.id;

            // Obtener estadísticas de la empresa
            Promise.all([
                fetchWithAuth(`http://127.0.0.1:8000/api/empresas/actividades`).then(r => r.json()),
                fetchWithAuth(`http://127.0.0.1:8000/api/empresas/reservas`).then(r => r.json())
            ])
            .then(([actividadesData, reservasData]) => {
                const totalActividades = actividadesData.success ? actividadesData.data.length : 0;
                const totalReservas = reservasData.success ? reservasData.data.length : 0;

                // Calcular reservas confirmadas
                const reservasConfirmadas = reservasData.success ?
                    reservasData.data.filter(r => r.estado === 'confirmada').length : 0;

                statsSection.innerHTML = `
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-8 rounded-3xl border border-blue-200 shadow-lg hover:shadow-xl transition duration-300">
                        <div class="flex items-center justify-center mb-4">
                            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <div class="text-center">
                                <h3 class="text-4xl font-bold text-blue-600">${totalActividades}</h3>
                                <p class="text-blue-700 font-medium">Actividades Creadas</p>
                            </div>
                        </div>
                        <p class="text-blue-600 text-center text-sm">Experiencias turísticas activas</p>
                    </div>
                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-8 rounded-3xl border border-green-200 shadow-lg hover:shadow-xl transition duration-300">
                        <div class="flex items-center justify-center mb-4">
                            <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="text-center">
                                <h3 class="text-4xl font-bold text-green-600">${reservasConfirmadas}</h3>
                                <p class="text-green-700 font-medium">Reservas Confirmadas</p>
                            </div>
                        </div>
                        <p class="text-green-600 text-center text-sm">Aventureros confirmados</p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-8 rounded-3xl border border-purple-200 shadow-lg hover:shadow-xl transition duration-300">
                        <div class="flex items-center justify-center mb-4">
                            <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="text-center">
                                <h3 class="text-4xl font-bold text-purple-600">${totalReservas}</h3>
                                <p class="text-purple-700 font-medium">Total Reservas</p>
                            </div>
                        </div>
                        <p class="text-purple-600 text-center text-sm">Solicitudes recibidas</p>
                    </div>
                `;
            })
            .catch(error => {
                console.error('Error cargando estadísticas:', error);
                statsSection.innerHTML = `
                    <div class="bg-red-50 p-6 rounded-lg text-center">
                        <h3 class="text-2xl font-bold text-red-600">Error</h3>
                        <p class="text-gray-600">No se pudieron cargar las estadísticas</p>
                    </div>
                `;
            });
        }
    })
    .catch(error => {
        console.error('Error obteniendo datos de empresa:', error);
        statsSection.innerHTML = `
            <div class="bg-red-50 p-6 rounded-lg text-center">
                <h3 class="text-2xl font-bold text-red-600">Error</h3>
                <p class="text-gray-600">No se pudieron cargar las estadísticas</p>
            </div>
        `;
    });
}

function loadActivities() {
    const listSection = document.getElementById('list-section');

    // Cargar actividades de la empresa autenticada
    fetch(`http://127.0.0.1:8000/api/empresas/actividades`, {
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success || data.data.length === 0) {
            listSection.innerHTML = `
                <div class="text-center py-20 bg-gradient-to-br from-green-50 to-blue-50 rounded-3xl">
                    <div class="text-green-400 text-8xl mb-6">🎯</div>
                    <h4 class="text-2xl font-semibold text-gray-700 mb-4">No has creado actividades aún</h4>
                    <p class="text-gray-600 mb-8 text-lg">¡Es hora de compartir tus experiencias con el mundo!</p>
                    <button onclick="createActivity()" class="bg-gradient-to-r from-green-600 to-green-700 text-white px-10 py-4 rounded-2xl hover:from-green-700 hover:to-green-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 text-lg">
                        Crear Primera Actividad
                    </button>
                </div>
            `;
        } else {
            let html = '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">';

            data.data.forEach(actividad => {
                const ocupacionPorcentaje = actividad.cupo_maximo > 0 ? Math.round((actividad.total_reservas / actividad.cupo_maximo) * 100) : 0;
                const ocupacionColor = ocupacionPorcentaje >= 80 ? 'bg-red-500' : ocupacionPorcentaje >= 50 ? 'bg-yellow-500' : 'bg-green-500';

                html += `
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition duration-300 group">
                        <div class="relative overflow-hidden">
                            <img src="${actividad.imagen || 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'}"
                                 alt="${actividad.nombre}"
                                 class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-110"
                                 onerror="this.src='https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'">
                            <div class="absolute top-4 right-4 bg-white bg-opacity-90 backdrop-blur-sm rounded-full px-3 py-1 shadow-lg">
                                <span class="text-sm font-bold text-gray-800">$${actividad.precio}</span>
                            </div>
                            <div class="absolute bottom-4 left-4 right-4">
                                <div class="bg-gradient-to-t from-black to-transparent rounded-lg p-4">
                                    <h4 class="text-xl font-bold text-white mb-1">${actividad.nombre}</h4>
                                    <div class="flex items-center text-white text-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        ${actividad.ubicacion}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">${actividad.descripcion}</p>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <div class="font-medium">${new Date(actividad.fecha).toLocaleDateString('es-ES')}</div>
                                        <div class="text-xs">${actividad.hora}</div>
                                    </div>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                    <div>
                                        <div class="font-medium">${actividad.total_reservas}/${actividad.cupo_maximo}</div>
                                        <div class="text-xs">reservas</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-600">Ocupación</span>
                                    <span class="text-sm font-medium">${ocupacionPorcentaje}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="${ocupacionColor} h-2 rounded-full transition-all duration-300" style="width: ${ocupacionPorcentaje}%"></div>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <button onclick="editActivity(${actividad.id})" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-3 rounded-xl hover:from-blue-700 hover:to-blue-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 text-sm">
                                    Editar
                                </button>
                                <button onclick="deleteActivity(${actividad.id})" class="flex-1 bg-gradient-to-r from-red-600 to-red-700 text-white px-4 py-3 rounded-xl hover:from-red-700 hover:to-red-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 text-sm">
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            listSection.innerHTML = html;
        }
    })
    .catch(error => {
        console.error('Error cargando actividades:', error);
        listSection.innerHTML = `
            <div class="text-center py-20 bg-red-50 rounded-3xl">
                <div class="text-red-400 text-8xl mb-6">⚠️</div>
                <h4 class="text-2xl font-semibold text-gray-700 mb-4">Error al cargar actividades</h4>
                <p class="text-gray-600 text-lg">Por favor, intenta nuevamente más tarde</p>
            </div>
        `;
    });
}

// Funciones de acción
function createActivity() {
    // Cargar categorías y municipios antes de mostrar el modal
    Promise.all([
        loadCategoriesForActivity(),
        loadMunicipiosForActivity()
    ]).then(() => {
        document.getElementById('createActivityModal').classList.remove('hidden');
    }).catch(error => {
        console.error('Error cargando datos para crear actividad:', error);
        alert('Error al cargar los datos necesarios para crear la actividad');
    });
}

function manageActivities() {
    loadActivities(); // Recargar la lista de actividades
}

function viewReservations() {
    // Mostrar sección de reservas
    document.getElementById('list-section').parentElement.style.display = 'none';
    document.getElementById('reservas-section').style.display = 'block';
    loadReservations();
}

function volverAActividades() {
    // Volver a la sección de actividades
    document.getElementById('reservas-section').style.display = 'none';
    document.getElementById('list-section').parentElement.style.display = 'block';
}

function loadReservations() {
    fetch(`http://127.0.0.1:8000/api/empresas/reservas`, {
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        }
    })
    .then(response => response.json())
    .then(data => {
        const reservasContent = document.getElementById('reservas-content');

        if (!data.success || data.data.length === 0) {
            reservasContent.innerHTML = `
                <div class="text-center py-20 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-3xl">
                    <div class="text-blue-400 text-8xl mb-6">📋</div>
                    <h4 class="text-2xl font-semibold text-gray-700 mb-4">No hay reservas aún</h4>
                    <p class="text-gray-600 text-lg">Tus actividades están esperando a sus primeros aventureros</p>
                </div>
            `;
        } else {
            let html = '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">';

            data.data.forEach(reserva => {
                const estadoClass = reserva.estado === 'confirmada' ? 'bg-green-100 text-green-800 border-green-200' :
                                   reserva.estado === 'cancelada' ? 'bg-red-100 text-red-800 border-red-200' :
                                   'bg-yellow-100 text-yellow-800 border-yellow-200';

                html += `
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition duration-300">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                            <h4 class="text-xl font-bold text-white">${reserva.actividad.nombre}</h4>
                            <p class="text-blue-100 text-sm">${new Date(reserva.fecha_reserva).toLocaleDateString('es-ES')}</p>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3 mb-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Usuario:</span>
                                    <span class="font-medium">${reserva.usuario.nombre}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Personas:</span>
                                    <span class="font-medium">${reserva.numero_personas}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Total:</span>
                                    <span class="font-bold text-green-600">$${reserva.total}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Estado:</span>
                                    <span class="px-3 py-1 rounded-full text-sm font-medium ${estadoClass}">${reserva.estado}</span>
                                </div>
                            </div>
                            ${reserva.estado === 'pendiente' ? `
                                <div class="flex gap-3">
                                    <button onclick="confirmarReserva(${reserva.id})" class="flex-1 bg-gradient-to-r from-green-600 to-green-700 text-white px-4 py-3 rounded-xl hover:from-green-700 hover:to-green-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
                                        Confirmar
                                    </button>
                                    <button onclick="cancelarReserva(${reserva.id})" class="flex-1 bg-gradient-to-r from-red-600 to-red-700 text-white px-4 py-3 rounded-xl hover:from-red-700 hover:to-red-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
                                        Cancelar
                                    </button>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            reservasContent.innerHTML = html;
        }
    })
    .catch(error => {
        console.error('Error cargando reservas:', error);
        document.getElementById('reservas-content').innerHTML = `
            <div class="text-center py-20 bg-red-50 rounded-3xl">
                <div class="text-red-400 text-8xl mb-6">⚠️</div>
                <h4 class="text-2xl font-semibold text-gray-700 mb-4">Error al cargar reservas</h4>
                <p class="text-gray-600 text-lg">Por favor, intenta nuevamente más tarde</p>
            </div>
        `;
    });
}

function confirmarReserva(reservaId) {
    fetch(`http://127.0.0.1:8000/api/empresas/reservas/${reservaId}/confirmar`, {
        method: 'PUT',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        }
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('Reserva confirmada exitosamente');
            viewReservations(); // Recargar lista
        } else {
            alert('Error al confirmar reserva: ' + (result.message || 'Error desconocido'));
        }
    })
    .catch(error => {
        console.error('Error confirmando reserva:', error);
        alert('Error al confirmar la reserva');
    });
}

function cancelarReserva(reservaId) {
    fetch(`http://127.0.0.1:8000/api/empresas/reservas/${reservaId}/cancelar`, {
        method: 'PUT',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        }
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('Reserva cancelada exitosamente');
            viewReservations(); // Recargar lista
        } else {
            alert('Error al cancelar reserva: ' + (result.message || 'Error desconocido'));
        }
    })
    .catch(error => {
        console.error('Error cancelando reserva:', error);
        alert('Error al cancelar la reserva');
    });
}

function loadCategoriesForActivity() {
    return fetch('http://127.0.0.1:8000/api/categories/active')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('idCategoria');
            select.innerHTML = '<option value="">Seleccionar categoría</option>';
            if (Array.isArray(data)) {
                data.forEach(cat => {
                    select.innerHTML += `<option value="${cat.id}">${cat.nombre}</option>`;
                });
            }
            return data;
        })
        .catch(error => {
            console.error('Error cargando categorías:', error);
            throw error;
        });
}

function loadMunicipiosForActivity() {
    return fetch('http://127.0.0.1:8000/api/listarMunicipios')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('idMunicipio');
            select.innerHTML = '<option value="">Seleccionar municipio</option>';
            data.forEach(mun => {
                select.innerHTML += `<option value="${mun.id}">${mun.Nombre_Municipio}</option>`;
            });
            return data;
        })
        .catch(error => {
            console.error('Error cargando municipios:', error);
            throw error;
        });
}

function closeCreateActivityModal() {
    document.getElementById('createActivityModal').classList.add('hidden');
    document.getElementById('createActivityForm').reset();
}

function editActivity(activityId) {
    // Implementar edición de actividad
    alert('Funcionalidad de edición próximamente disponible (ID: ' + activityId + ')');
}

function deleteActivity(activityId) {
    if (!confirm('¿Estás seguro de que quieres eliminar esta actividad? Esta acción no se puede deshacer.')) {
        return;
    }

    fetch(`http://127.0.0.1:8000/api/empresas/actividades/${activityId}`, {
        method: 'DELETE',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        }
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('Actividad eliminada exitosamente');
            loadActivities(); // Recargar lista
            loadStats(); // Recargar estadísticas
        } else {
            alert('Error al eliminar actividad: ' + (result.message || 'Error desconocido'));
        }
    })
    .catch(error => {
        console.error('Error eliminando actividad:', error);
        alert('Error al eliminar la actividad');
    });
}

// Gestión de perfil
function showSection(section) {
    // Ocultar todas las secciones principales
    document.querySelector('main').style.display = 'none';

    // Mostrar la sección solicitada
    document.getElementById(section + '-section').classList.remove('hidden');

    if (section === 'perfil') {
        loadPerfilEmpresa();
    } else if (section === 'empleados') {
        loadEmpleados();
    }
}

function volverAInicio() {
    // Ocultar secciones
    document.getElementById('perfil-section').classList.add('hidden');
    document.getElementById('empleados-section').classList.add('hidden');

    // Mostrar sección principal
    document.querySelector('main').style.display = 'block';
}

function loadPerfilEmpresa() {
    fetchWithAuth('http://127.0.0.1:8000/api/empresas/me')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const empresa = data.empresa;
            document.getElementById('view-nombre').textContent = empresa.nombre || 'No especificado';
            document.getElementById('view-numero').textContent = empresa.numero || 'No especificado';
            document.getElementById('view-direccion').textContent = empresa.direccion || 'No especificado';
            document.getElementById('view-ciudad').textContent = empresa.ciudad || 'No especificado';
            document.getElementById('view-correo').textContent = empresa.correo || 'No especificado';
            document.getElementById('view-telefono').textContent = empresa.telefono || 'No especificado';
            document.getElementById('view-sitio_web').textContent = empresa.sitio_web || 'No especificado';
            document.getElementById('view-descripcion').textContent = empresa.descripcion || 'No hay descripción disponible';
        }
    })
    .catch(error => {
        console.error('Error cargando perfil:', error);
        showNotification('Error al cargar el perfil', 'error');
    });
}

function entrarModoEdicionPerfil() {
    // Cargar datos actuales en el formulario
    fetchWithAuth('http://127.0.0.1:8000/api/empresas/me')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const empresa = data.empresa;
            document.getElementById('perfil-nombre').value = empresa.nombre || '';
            document.getElementById('perfil-numero').value = empresa.numero || '';
            document.getElementById('perfil-direccion').value = empresa.direccion || '';
            document.getElementById('perfil-ciudad').value = empresa.ciudad || '';
            document.getElementById('perfil-telefono').value = empresa.telefono || '';
            document.getElementById('perfil-sitio_web').value = empresa.sitio_web || '';
            document.getElementById('perfil-descripcion').value = empresa.descripcion || '';

            document.getElementById('perfil-view-mode').classList.add('hidden');
            document.getElementById('perfil-edit-mode').classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error('Error cargando datos para edición:', error);
        showNotification('Error al cargar datos para edición', 'error');
    });
}

function guardarPerfilEmpresa() {
    const data = {
        nombre: document.getElementById('perfil-nombre').value,
        numero: document.getElementById('perfil-numero').value,
        descripcion: document.getElementById('perfil-descripcion').value,
        direccion: document.getElementById('perfil-direccion').value,
        ciudad: document.getElementById('perfil-ciudad').value,
        telefono: document.getElementById('perfil-telefono').value,
        sitio_web: document.getElementById('perfil-sitio_web').value,
    };

    fetchWithAuth('http://127.0.0.1:8000/api/empresas/me', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showNotification('Perfil actualizado exitosamente', 'success');
            loadPerfilEmpresa();
            cancelarEdicionPerfil();
        } else {
            showNotification(result.message || 'Error al actualizar perfil', 'error');
        }
    })
    .catch(error => {
        console.error('Error guardando perfil:', error);
        showNotification('Error al guardar los cambios', 'error');
    });
}

function cancelarEdicionPerfil() {
    document.getElementById('perfil-edit-mode').classList.add('hidden');
    document.getElementById('perfil-view-mode').classList.remove('hidden');
}

// Gestión de empleados
function loadEmpleados() {
    fetchWithAuth('http://127.0.0.1:8000/api/empresas/empleados')
    .then(response => response.json())
    .then(data => {
        const empleadosList = document.getElementById('empleados-list');
        const empleadosEmpty = document.getElementById('empleados-empty');

        if (!data.success || data.empleados.length === 0) {
            empleadosList.innerHTML = '';
            empleadosEmpty.classList.remove('hidden');
        } else {
            empleadosEmpty.classList.add('hidden');
            let html = '';

            data.empleados.forEach(empleado => {
                html += `
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition duration-300">
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                            <h4 class="text-xl font-bold text-white">${empleado.nombre}</h4>
                            <p class="text-indigo-100 text-sm">${empleado.email}</p>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3 mb-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Rol:</span>
                                    <span class="font-medium">${empleado.rol || 'Sin asignar'}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Teléfono:</span>
                                    <span class="font-medium">${empleado.telefono || 'No especificado'}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Fecha de registro:</span>
                                    <span class="font-medium">${new Date(empleado.fecha_registro).toLocaleDateString('es-ES')}</span>
                                </div>
                            </div>
                            <button onclick="removerEmpleado(${empleado.id})" class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white px-4 py-3 rounded-xl hover:from-red-700 hover:to-red-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
                                Remover Empleado
                            </button>
                        </div>
                    </div>
                `;
            });

            empleadosList.innerHTML = html;
        }
    })
    .catch(error => {
        console.error('Error cargando empleados:', error);
        showNotification('Error al cargar empleados', 'error');
    });
}

function mostrarModalAsignarEmpleado() {
    document.getElementById('asignarEmpleadoModal').classList.remove('hidden');
}

function closeAsignarEmpleadoModal() {
    document.getElementById('asignarEmpleadoModal').classList.add('hidden');
    document.getElementById('asignarEmpleadoForm').reset();
}

function removerEmpleado(usuarioId) {
    if (!confirm('¿Estás seguro de que quieres remover este empleado de tu empresa?')) {
        return;
    }

    fetchWithAuth(`http://127.0.0.1:8000/api/empresas/empleados/${usuarioId}`, {
        method: 'DELETE'
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showNotification('Empleado removido exitosamente', 'success');
            loadEmpleados();
        } else {
            showNotification(result.message || 'Error al remover empleado', 'error');
        }
    })
    .catch(error => {
        console.error('Error removiendo empleado:', error);
        showNotification('Error al remover empleado', 'error');
    });
}

// Función auxiliar para mostrar notificaciones
function showNotification(message, type = 'info') {
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
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

// Manejadores de formularios
function handleCreateActivity() {
    // Obtener el ID de la empresa
    fetchWithAuth('http://127.0.0.1:8000/api/empresas/me')
    .then(response => response.json())
    .then(userData => {
        if (userData.success && userData.empresa) {
            const empresaId = userData.empresa.id;

            const formData = new FormData(document.getElementById('createActivityForm'));
            const data = Object.fromEntries(formData);

            fetch(`http://127.0.0.1:8000/api/empresas/actividades`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    showNotification('Actividad creada exitosamente', 'success');
                    closeCreateActivityModal();
                    loadActivities(); // Recargar lista
                    loadStats(); // Recargar estadísticas
                } else {
                    showNotification('Error al crear actividad: ' + JSON.stringify(result.errors || result.message), 'error');
                }
            })
            .catch(error => {
                console.error('Error creando actividad:', error);
                if (error.message !== 'Unauthorized') {
                    showNotification('Error al crear actividad: ' + error.message, 'error');
                }
            });
        } else {
            showNotification('Error: No se pudo obtener la información de la empresa', 'error');
        }
    })
    .catch(error => {
        console.error('Error obteniendo datos de empresa:', error);
        showNotification('Error al obtener información de la empresa', 'error');
    });
}

function handleAsignarEmpleado() {
    const formData = new FormData(document.getElementById('asignarEmpleadoForm'));
    const data = Object.fromEntries(formData);

    fetchWithAuth('http://127.0.0.1:8000/api/empresas/empleados', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showNotification('Empleado asignado exitosamente', 'success');
            closeAsignarEmpleadoModal();
            loadEmpleados();
        } else {
            showNotification(result.message || 'Error al asignar empleado', 'error');
        }
    })
    .catch(error => {
        console.error('Error asignando empleado:', error);
        showNotification('Error al asignar empleado', 'error');
    });
}