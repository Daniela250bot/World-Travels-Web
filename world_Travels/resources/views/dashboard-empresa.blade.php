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
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 transition">Mi Dashboard</a>
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

        <!-- Modal para crear actividad -->
        <div id="createActivityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Crear Nueva Actividad</h3>
                    <form id="createActivityForm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Nombre de la Actividad</label>
                                <input type="text" id="Nombre_Actividad" name="Nombre_Actividad" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Descripción</label>
                                <textarea id="Descripcion" name="Descripcion" rows="3" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Categoría</label>
                                <select id="idCategoria" name="idCategoria" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Seleccionar categoría</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Municipio</label>
                                <select id="idMunicipio" name="idMunicipio" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Seleccionar municipio</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fecha de la Actividad</label>
                                <input type="date" id="Fecha_Actividad" name="Fecha_Actividad" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Hora de la Actividad</label>
                                <input type="time" id="Hora_Actividad" name="Hora_Actividad" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Precio</label>
                                <input type="number" id="Precio" name="Precio" step="0.01" min="0" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cupo Máximo</label>
                                <input type="number" id="Cupo_Maximo" name="Cupo_Maximo" min="1" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Ubicación</label>
                                <input type="text" id="Ubicacion" name="Ubicacion" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Imagen (URL)</label>
                                <input type="url" id="Imagen" name="Imagen" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        <div class="flex justify-end mt-6 space-x-3">
                            <button type="button" onclick="closeCreateActivityModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-md hover:bg-gray-300">Cancelar</button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">Crear Actividad</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Guardar el token JWT en localStorage después del login
        @if(isset($jwtToken) && $jwtToken)
            localStorage.setItem('token', '{{ $jwtToken }}');
            localStorage.setItem('user_role', 'empresa');
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

        // Función auxiliar para obtener información del usuario/empresa autenticado
        function getAuthenticatedUser() {
            return fetchWithAuth('http://127.0.0.1:8000/api/empresas/me')
            .then(response => response.json())
            .catch(error => {
                // Si falla como empresa, intentar como usuario regular
                if (error.message === 'Token expired') {
                    return fetchWithAuth('http://127.0.0.1:8000/api/me').then(r => r.json());
                }
                throw error;
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
            loadActivities();
        });

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
                            <div class="bg-blue-50 p-6 rounded-lg text-center">
                                <h3 class="text-2xl font-bold text-blue-600">${totalActividades}</h3>
                                <p class="text-gray-600">Actividades Creadas</p>
                            </div>
                            <div class="bg-green-50 p-6 rounded-lg text-center">
                                <h3 class="text-2xl font-bold text-green-600">${reservasConfirmadas}</h3>
                                <p class="text-gray-600">Reservas Confirmadas</p>
                            </div>
                            <div class="bg-yellow-50 p-6 rounded-lg text-center">
                                <h3 class="text-2xl font-bold text-yellow-600">${totalReservas}</h3>
                                <p class="text-gray-600">Total Reservas</p>
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

            // Obtener el ID de la empresa desde empresas/me
            fetch('http://127.0.0.1:8000/api/empresas/me', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(userData => {
                if (userData.success && userData.empresa) {
                    const empresaId = userData.empresa.id;

                    // Cargar actividades de la empresa autenticada
                    fetch(`http://127.0.0.1:8000/api/empresas/actividades`, {
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('token')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        let html = '<h3 class="text-2xl font-bold mb-6 text-gray-800">Mis Actividades</h3>';
                        if (!data.success || data.data.length === 0) {
                            html += '<p class="text-gray-600">No has creado actividades aún.</p>';
                        } else {
                            html += '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">';
                            data.data.forEach(actividad => {
                                html += `
                                    <div class="border rounded-lg p-4">
                                        <img src="${actividad.imagen || 'https://via.placeholder.com/300x200?text=Actividad'}" alt="${actividad.nombre}" class="w-full h-32 object-cover rounded mb-2">
                                        <h4 class="font-semibold">${actividad.nombre}</h4>
                                        <p class="text-sm text-gray-600">${actividad.descripcion}</p>
                                        <p class="text-sm">Precio: $${actividad.precio}</p>
                                        <p class="text-sm">Cupo: ${actividad.cupo_maximo}</p>
                                        <p class="text-sm">Reservas: ${actividad.total_reservas}/${actividad.cupo_maximo}</p>
                                    </div>
                                `;
                            });
                            html += '</div>';
                        }
                        listSection.innerHTML = html;
                    })
                    .catch(error => console.error('Error cargando actividades:', error));
                }
            })
            .catch(error => console.error('Error obteniendo datos de empresa:', error));
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
            // Cargar reservas de la empresa autenticada
            loadReservations();
        }

        function loadReservations() {
            fetch(`http://127.0.0.1:8000/api/empresas/reservas`, {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(data => {
                const listSection = document.getElementById('list-section');
                let html = '<h3 class="text-2xl font-bold mb-6 text-gray-800">Reservas Recibidas</h3>';

                if (!data.success || data.data.length === 0) {
                    html += '<p class="text-gray-600">No hay reservas aún.</p>';
                } else {
                    html += '<div class="overflow-x-auto">';
                    html += '<table class="w-full table-auto border-collapse border border-gray-300">';
                    html += '<thead><tr class="bg-gray-100"><th class="border border-gray-300 px-4 py-2">Actividad</th><th class="border border-gray-300 px-4 py-2">Usuario</th><th class="border border-gray-300 px-4 py-2">Personas</th><th class="border border-gray-300 px-4 py-2">Estado</th><th class="border border-gray-300 px-4 py-2">Total</th><th class="border border-gray-300 px-4 py-2">Acciones</th></tr></thead>';
                    html += '<tbody>';

                    data.data.forEach(reserva => {
                        html += `
                            <tr class="border-b">
                                <td class="px-4 py-2">${reserva.actividad.nombre}</td>
                                <td class="px-4 py-2">${reserva.usuario.nombre}</td>
                                <td class="px-4 py-2">${reserva.numero_personas}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded text-sm ${reserva.estado === 'confirmada' ? 'bg-green-100 text-green-800' : reserva.estado === 'cancelada' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'}">
                                        ${reserva.estado}
                                    </span>
                                </td>
                                <td class="px-4 py-2">$${reserva.total}</td>
                                <td class="px-4 py-2">
                                    ${reserva.estado === 'pendiente' ? `
                                        <button onclick="confirmarReserva(${reserva.id})" class="bg-green-500 text-white px-2 py-1 rounded text-sm mr-2">Confirmar</button>
                                        <button onclick="cancelarReserva(${reserva.id})" class="bg-red-500 text-white px-2 py-1 rounded text-sm">Cancelar</button>
                                    ` : 'N/A'}
                                </td>
                            </tr>
                        `;
                    });

                    html += '</tbody></table></div>';
                }

                listSection.innerHTML = html;
            })
            .catch(error => {
                console.error('Error cargando reservas:', error);
                alert('Error al cargar las reservas');
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

        // Event listener para el formulario de crear actividad
        document.getElementById('createActivityForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Obtener el ID de la empresa
            fetch('http://127.0.0.1:8000/api/empresas/me', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(userData => {
                if (userData.success && userData.empresa) {
                    const empresaId = userData.empresa.id;

                    const formData = new FormData(this);
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
                            alert('Actividad creada exitosamente');
                            closeCreateActivityModal();
                            loadActivities(); // Recargar lista
                            loadStats(); // Recargar estadísticas
                        } else {
                            alert('Error al crear actividad: ' + JSON.stringify(result.errors || result.message));
                        }
                    })
                    .catch(error => {
                        console.error('Error creando actividad:', error);
                        if (error.message !== 'Unauthorized') {
                            alert('Error al crear actividad: ' + error.message);
                        }
                    });
                } else {
                    alert('Error: No se pudo obtener la información de la empresa');
                }
            })
            .catch(error => {
                console.error('Error obteniendo datos de empresa:', error);
                alert('Error al obtener información de la empresa');
            });
        });
    </script>
</body>
</html>
