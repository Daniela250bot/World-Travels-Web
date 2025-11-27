<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - WORLD TRAVELS</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Variable para JavaScript -->
    <meta name="jwt-token" content="{{ session('jwt_token', '') }}">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-blue-600">WORLD TRAVELS</h1>
            <nav class="space-x-6">
                <a href="{{ route('dashboard-administrador') }}" class="text-gray-700 hover:text-blue-600 transition">Panel Administrativo</a>
                <a href="{{ route('search') }}" class="text-gray-700 hover:text-blue-600 transition">Buscar Actividades</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="text-gray-700 hover:text-blue-600 transition bg-transparent border-none cursor-pointer">Cerrar Sesión</button>
                </form>
            </nav>
        </div>
    </header>

    <!-- Perfil del Administrador - Pantalla Independiente -->
    <main class="min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-3xl shadow-2xl p-8 border border-blue-100">
                <!-- Header del Perfil -->
                <div class="text-center mb-8">
                    <div id="foto-perfil-container-view" class="relative inline-block mb-6">
                        <img id="foto-perfil-preview-view" src="" alt="Foto de perfil"
                              class="w-40 h-40 rounded-full object-cover border-8 border-white shadow-xl mx-auto">
                        <div id="foto-perfil-placeholder-view" class="w-40 h-40 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold text-6xl mx-auto shadow-xl">
                            A
                        </div>
                    </div>
                    <h1 id="perfil-nombre-completo" class="text-4xl font-bold text-gray-800 mb-2">Administrador</h1>
                    <p class="text-lg text-gray-600">GESTIÓN TOTAL DEL SISTEMA WORLD TRAVELS</p>
                </div>

                <!-- Información del Perfil en Modo Vista -->
                <div id="perfil-view-mode" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-xl p-6 shadow-md border border-gray-100">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800">Información Personal</h4>
                            </div>
                            <div class="space-y-2 text-gray-700">
                                <p><span class="font-medium">Nombre:</span> <span id="view-nombre">Cargando...</span></p>
                                <p><span class="font-medium">Apellido:</span> <span id="view-apellido">Cargando...</span></p>
                                <p><span class="font-medium">Documento:</span> <span id="view-documento">Cargando...</span></p>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl p-6 shadow-md border border-gray-100">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800">Contacto</h4>
                            </div>
                            <div class="space-y-2 text-gray-700">
                                <p><span class="font-medium">Email:</span> <span id="view-email">Cargando...</span></p>
                                <p><span class="font-medium">Teléfono:</span> <span id="view-telefono">Cargando...</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-md border border-gray-100">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-800">Información del Sistema</h4>
                        </div>
                        <div class="space-y-2 text-gray-700">
                            <p><span class="font-medium">Rol:</span> Administrador del Sistema</p>
                            <p><span class="font-medium">Nivel de Acceso:</span> Completo</p>
                            <p><span class="font-medium">Funciones:</span> Gestión total del sistema WORLD TRAVELS</p>
                            <p><span class="font-medium">ID de Usuario:</span> <span id="view-id">Cargando...</span></p>
                            <p><span class="font-medium">Fecha de Registro:</span> <span id="view-fecha-registro">Cargando...</span></p>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center pt-6 border-t border-gray-200">
                        <button onclick="entrarModoEdicion()" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-3 rounded-xl hover:from-blue-700 hover:to-blue-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Editar Perfil
                        </button>
                        <button onclick="eliminarPerfil()" class="bg-gradient-to-r from-red-600 to-red-700 text-white px-8 py-3 rounded-xl hover:from-red-700 hover:to-red-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Eliminar Cuenta
                        </button>
                    </div>
                </div>

                <!-- Modo Edición (oculto por defecto) -->
                <div id="perfil-edit-mode" class="hidden space-y-6">
                    <div class="text-center mb-6">
                        <h4 class="text-2xl font-bold text-gray-800">Editar Información</h4>
                        <p class="text-gray-600">Actualiza tu información personal</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                            <input type="text" id="perfil-nombre" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Apellido</label>
                            <input type="text" id="perfil-apellido" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="perfil-email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                            <input type="tel" id="perfil-telefono" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Documento</label>
                        <input type="text" id="perfil-documento" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nueva Contraseña (opcional)</label>
                        <input type="password" id="perfil-contraseña" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Dejar vacío para no cambiar">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirmar Contraseña</label>
                        <input type="password" id="perfil-confirmar-contraseña" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Gestión de Foto de Perfil -->
                    <div class="border-t pt-6">
                        <h5 class="text-lg font-semibold text-gray-800 mb-4">Foto de Perfil</h5>
                        <div class="flex flex-col sm:flex-row items-center gap-4">
                            <div id="foto-perfil-container" class="relative">
                                <img id="foto-perfil-preview" src="" alt="Foto de perfil"
                                      class="w-24 h-24 rounded-full object-cover border-4 border-gray-200">
                                <div id="foto-perfil-placeholder" class="w-24 h-24 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-bold text-xl">
                                    A
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <input type="file" id="foto-perfil-input" accept="image/*" class="hidden">
                                <button onclick="document.getElementById('foto-perfil-input').click()"
                                        class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition duration-300 font-medium">
                                    Cambiar Foto
                                </button>
                                <button onclick="eliminarFotoPerfil()" id="btn-eliminar-foto" class="bg-red-100 text-red-700 px-4 py-2 rounded-lg hover:bg-red-200 transition duration-300 font-medium hidden">
                                    Eliminar Foto
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción en Modo Edición -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center pt-6 border-t border-gray-200">
                        <button onclick="guardarPerfil()" class="bg-gradient-to-r from-green-600 to-green-700 text-white px-8 py-3 rounded-xl hover:from-green-700 hover:to-green-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Guardar Cambios
                        </button>
                        <button onclick="cancelarEdicion()" class="bg-gray-500 text-white px-8 py-3 rounded-xl hover:bg-gray-600 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <script>
        const jwtToken = document.querySelector('meta[name="jwt-token"]').getAttribute('content');
        if (jwtToken) {
            localStorage.setItem('token', jwtToken);
            localStorage.setItem('user_role', 'administrador');
        }

        let currentUser = null;

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

        // ==================== INICIALIZACIÓN ====================
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM cargado, inicializando perfil de administrador...');

            const token = localStorage.getItem('token');
            console.log('Token encontrado:', !!token);

            if (!token) {
                console.warn('No hay token, redirigiendo al login');
                window.location.href = '{{ route("login") }}';
                return;
            }

            console.log('Cargando datos del perfil...');
            loadPerfil();

            // Agregar event listener para subida de foto de perfil
            const fotoInput = document.getElementById('foto-perfil-input');
            if (fotoInput) {
                fotoInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        subirFotoPerfil(file);
                    }
                });
            }
        });

        function loadPerfil() {
            console.log('Cargando perfil del administrador...');

            // Cargar información del perfil
            fetchWithAuth('http://127.0.0.1:8000/api/admin/me')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.administrador) {
                    const admin = data.administrador;

                    // Llenar modo vista
                    document.getElementById('view-nombre').textContent = admin.nombre || 'No especificado';
                    document.getElementById('view-apellido').textContent = admin.apellido || 'No especificado';
                    document.getElementById('view-email').textContent = admin.correo_electronico || 'No especificado';
                    document.getElementById('view-telefono').textContent = admin.telefono || 'No especificado';
                    document.getElementById('view-documento').textContent = admin.documento || 'No especificado';
                    document.getElementById('view-id').textContent = admin.id || 'N/A';
                    document.getElementById('view-fecha-registro').textContent = admin.fecha_registro ? new Date(admin.fecha_registro).toLocaleDateString() : 'No disponible';

                    // Nombre completo en el header
                    const nombreCompleto = `${admin.nombre || ''} ${admin.apellido || ''}`.trim() || 'Administrador';
                    document.getElementById('perfil-nombre-completo').textContent = nombreCompleto;

                    // Llenar modo edición
                    document.getElementById('perfil-nombre').value = admin.nombre || '';
                    document.getElementById('perfil-apellido').value = admin.apellido || '';
                    document.getElementById('perfil-email').value = admin.correo_electronico || '';
                    document.getElementById('perfil-telefono').value = admin.telefono || '';
                    document.getElementById('perfil-documento').value = admin.documento || '';

                    // Mostrar foto de perfil
                    updateFotoPerfilPreview(admin.foto_perfil, admin.nombre);
                }
            })
            .catch(error => console.error('Error cargando información del perfil:', error));
        }

        function updateFotoPerfilPreview(fotoUrl, nombre) {
            // Actualizar foto en modo edición
            const container = document.getElementById('foto-perfil-container');
            const preview = document.getElementById('foto-perfil-preview');
            const placeholder = document.getElementById('foto-perfil-placeholder');
            const btnEliminar = document.getElementById('btn-eliminar-foto');

            // Actualizar foto en modo vista
            const containerView = document.getElementById('foto-perfil-container-view');
            const previewView = document.getElementById('foto-perfil-preview-view');
            const placeholderView = document.getElementById('foto-perfil-placeholder-view');

            const inicial = nombre ? nombre.charAt(0).toUpperCase() : 'A';

            if (fotoUrl) {
                const fotoSrc = fotoUrl.startsWith('http') ? fotoUrl : `/storage/${fotoUrl}`;

                // Modo edición
                if (preview) {
                    preview.src = fotoSrc;
                    preview.style.display = 'block';
                    if (placeholder) placeholder.style.display = 'none';
                    if (btnEliminar) btnEliminar.classList.remove('hidden');
                }

                // Modo vista
                if (previewView) {
                    previewView.src = fotoSrc;
                    previewView.style.display = 'block';
                    if (placeholderView) placeholderView.style.display = 'none';
                }
            } else {
                // Modo edición
                if (placeholder) {
                    placeholder.textContent = inicial;
                    placeholder.style.display = 'block';
                }
                if (preview) preview.style.display = 'none';
                if (btnEliminar) btnEliminar.classList.add('hidden');

                // Modo vista
                if (placeholderView) {
                    placeholderView.textContent = inicial;
                    placeholderView.style.display = 'block';
                }
                if (previewView) previewView.style.display = 'none';
            }
        }

        function entrarModoEdicion() {
            document.getElementById('perfil-view-mode').classList.add('hidden');
            document.getElementById('perfil-edit-mode').classList.remove('hidden');
        }

        function cancelarEdicion() {
            document.getElementById('perfil-edit-mode').classList.add('hidden');
            document.getElementById('perfil-view-mode').classList.remove('hidden');
        }

        function guardarPerfil() {
            const nombre = document.getElementById('perfil-nombre').value;
            const apellido = document.getElementById('perfil-apellido').value;
            const telefono = document.getElementById('perfil-telefono').value;
            const contraseña = document.getElementById('perfil-contraseña').value;
            const confirmarContraseña = document.getElementById('perfil-confirmar-contraseña').value;

            // Validación de contraseñas
            if (contraseña && contraseña !== confirmarContraseña) {
                showNotification('Las contraseñas no coinciden', 'error');
                return;
            }

            const data = {
                nombre: nombre,
                apellido: apellido,
                telefono: telefono
            };

            if (contraseña) {
                data.contraseña = contraseña;
                data.contraseña_confirmation = confirmarContraseña;
            }

            fetchWithAuth('http://127.0.0.1:8000/api/admin/perfil', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Perfil actualizado exitosamente', 'success');
                    // Recargar perfil para actualizar vista
                    loadPerfil();
                    // Volver al modo vista
                    cancelarEdicion();
                } else {
                    showNotification(data.message || 'Error al actualizar perfil', 'error');
                }
            })
            .catch(error => {
                console.error('Error guardando perfil:', error);
                showNotification('Error al guardar los cambios', 'error');
            });
        }

        function eliminarPerfil() {
            if (!confirm('¿Estás completamente seguro de que quieres eliminar tu cuenta?\n\nEsta acción no se puede deshacer y perderás:\n- Tu acceso de administrador\n- Todos tus datos personales\n\n¿Deseas continuar?')) {
                return;
            }

            if (!confirm('Última confirmación: ¿Realmente quieres eliminar permanentemente tu cuenta de administrador?')) {
                return;
            }

            fetchWithAuth('http://127.0.0.1:8000/api/admin/perfil', {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Cuenta eliminada exitosamente. Serás redirigido al inicio.', 'success');
                    // Limpiar token y redirigir
                    setTimeout(() => {
                        localStorage.removeItem('token');
                        window.location.href = '{{ route("home") }}';
                    }, 2000);
                } else {
                    showNotification(data.message || 'Error al eliminar cuenta', 'error');
                }
            })
            .catch(error => {
                console.error('Error eliminando perfil:', error);
                showNotification('Error al eliminar la cuenta', 'error');
            });
        }

        function subirFotoPerfil(file) {
            const formData = new FormData();
            formData.append('foto_perfil', file);

            // Mostrar loading
            const btnCambiar = document.querySelector('button[onclick*="foto-perfil-input"]');
            const originalText = btnCambiar.textContent;
            btnCambiar.disabled = true;
            btnCambiar.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-gray-700 mx-auto"></div>';

            fetchWithAuth('http://127.0.0.1:8000/api/admin/foto-perfil', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Foto de perfil actualizada exitosamente', 'success');
                    // Recargar perfil para actualizar todas las vistas
                    loadPerfil();
                } else {
                    showNotification(data.message || 'Error al subir foto', 'error');
                }
            })
            .catch(error => {
                console.error('Error subiendo foto:', error);
                showNotification('Error al subir la foto de perfil', 'error');
            })
            .finally(() => {
                // Restaurar botón
                btnCambiar.disabled = false;
                btnCambiar.textContent = originalText;
            });
        }

        function eliminarFotoPerfil() {
            if (!confirm('¿Estás seguro de que quieres eliminar tu foto de perfil?')) {
                return;
            }

            fetchWithAuth('http://127.0.0.1:8000/api/admin/foto-perfil', {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Foto de perfil eliminada exitosamente', 'success');
                    // Recargar perfil para actualizar todas las vistas
                    loadPerfil();
                } else {
                    showNotification(data.message || 'Error al eliminar foto', 'error');
                }
            })
            .catch(error => console.error('Error eliminando foto:', error));
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
    </script>
</body>
</html>