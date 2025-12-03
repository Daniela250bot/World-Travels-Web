@extends('components.dashboard-layout')

@section('title', 'Empresa - WORLD TRAVELS')

@section('nav-links')
    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 transition">Mi Dashboard</a>
@endsection

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-green-600 via-teal-600 to-blue-700 text-white py-20 overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="absolute inset-0" style="background-image: url('https://media.istockphoto.com/id/1289259699/es/vector/servicio-tur%C3%ADstico-con-gerente-de-empresas-de-viajes.jpg?s=612x612&w=0&k=20&c=UUoPpj1cHXREMWLTRkivZ5PrajIKnzFDiXxzdLsDXXI='); background-size: cover; background-position: center;"></div>
        <div class="relative container mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold mb-4">Panel de Empresa</h1>
            <p class="text-xl mb-8 max-w-2xl mx-auto">Bienvenido, <span id="user-name" class="font-semibold"></span>. Gestiona tus actividades turísticas y conecta con viajeros apasionados.</p>
            <div class="flex justify-center space-x-4">
                <button onclick="createActivity()" class="bg-white text-green-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition duration-300 transform hover:scale-105">
                    Crear Nueva Actividad
                </button>
                <button onclick="viewReservations()" class="border-2 border-white text-white px-8 py-3 rounded-full font-semibold hover:bg-white hover:text-green-600 transition duration-300">
                    Ver Reservas
                </button>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" class="w-full h-12 fill-white">
                <path d="M0,32L48,37.3C96,43,192,53,288,58.7C384,64,480,64,576,58.7C672,53,768,43,864,48C960,53,1056,75,1152,80C1248,85,1344,75,1392,69.3L1440,64L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z"></path>
            </svg>
        </div>
    </section>

    <main class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-green-50">
        <div class="max-w-6xl mx-auto px-8 py-16 -mt-16 relative z-10">
            <!-- Panel de Control Rápido -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 mb-12">
                <!-- Estadísticas rápidas -->
                <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-8" id="stats-section">
                    <!-- Estadísticas se cargarán aquí -->
                </div>

                <!-- Acciones Rápidas -->
                <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Acciones Rápidas
                    </h3>
                    <div class="space-y-4">
                        <button onclick="createActivity()" class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-4 rounded-2xl hover:from-green-700 hover:to-green-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center text-base">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Nueva Actividad
                        </button>
                        <button onclick="manageActivities()" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4 rounded-2xl hover:from-blue-700 hover:to-blue-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center text-base">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Gestionar Actividades
                        </button>
                        <button onclick="viewReservations()" class="w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-4 rounded-2xl hover:from-purple-700 hover:to-purple-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center text-base">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Ver Reservas
                        </button>
                        <button onclick="showSection('perfil')" class="w-full bg-gradient-to-r from-pink-600 to-pink-700 text-white px-6 py-4 rounded-2xl hover:from-pink-700 hover:to-pink-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center text-base">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Mi Perfil
                        </button>
                        <button onclick="showSection('empleados')" class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-6 py-4 rounded-2xl hover:from-indigo-700 hover:to-indigo-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center text-base">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Gestionar Empleados
                        </button>
                    </div>
                </div>
            </div>

            <!-- Secciones Principales -->
            <div class="space-y-16">
                <!-- Mis Actividades -->
                <section class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 px-10 py-8">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-4xl font-bold text-white">Mis Actividades</h2>
                                <p class="text-green-100 mt-2 text-lg">Gestiona tus experiencias turísticas y conecta con viajeros</p>
                            </div>
                            <button onclick="createActivity()" class="bg-white/20 backdrop-blur-sm text-white px-8 py-4 rounded-2xl hover:bg-white/30 transition duration-300 font-medium border border-white/30 flex items-center text-lg">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Crear Actividad
                            </button>
                        </div>
                    </div>

                    <div class="p-10" id="list-section">
                        <!-- Lista se cargará aquí -->
                    </div>
                </section>

                <!-- Reservas Recibidas -->
                <section class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden" id="reservas-section" style="display: none;">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-10 py-8">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-4xl font-bold text-white">Reservas Recibidas</h2>
                                <p class="text-blue-100 mt-2 text-lg">Gestiona las solicitudes de tus actividades</p>
                            </div>
                            <button onclick="volverAActividades()" class="bg-white/20 backdrop-blur-sm text-white px-8 py-4 rounded-2xl hover:bg-white/30 transition duration-300 font-medium border border-white/30 flex items-center text-lg">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Volver
                            </button>
                        </div>
                    </div>

                    <div class="p-10" id="reservas-content">
                        <!-- Reservas se cargarán aquí -->
                    </div>
                </section>
            </div>
        </div>

        <!-- Secciones Ocultas -->
        <div class="hidden" id="perfil-section">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-pink-600 to-pink-700 px-10 py-8">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-4xl font-bold text-white">Mi Perfil de Empresa</h2>
                            <p class="text-pink-100 mt-2 text-lg">Gestiona tu información empresarial</p>
                        </div>
                        <button onclick="volverAInicio()" class="bg-white/20 backdrop-blur-sm text-white px-8 py-4 rounded-2xl hover:bg-white/30 transition duration-300 font-medium border border-white/30 flex items-center text-lg">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Volver
                        </button>
                    </div>
                </div>

                <div class="p-10">
                    <!-- Información del Perfil -->
                    <div id="perfil-view-mode" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white rounded-xl p-6 shadow-md border border-gray-100">
                                <div class="flex items-center mb-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-800">Información Empresarial</h4>
                                </div>
                                <div class="space-y-2 text-gray-700">
                                    <p><span class="font-medium">Nombre:</span> <span id="view-nombre">Cargando...</span></p>
                                    <p><span class="font-medium">Número:</span> <span id="view-numero">Cargando...</span></p>
                                    <p><span class="font-medium">Dirección:</span> <span id="view-direccion">Cargando...</span></p>
                                    <p><span class="font-medium">Ciudad:</span> <span id="view-ciudad">Cargando...</span></p>
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
                                    <p><span class="font-medium">Email:</span> <span id="view-correo">Cargando...</span></p>
                                    <p><span class="font-medium">Teléfono:</span> <span id="view-telefono">Cargando...</span></p>
                                    <p><span class="font-medium">Sitio Web:</span> <span id="view-sitio_web">Cargando...</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl p-6 shadow-md border border-gray-100">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800">Descripción</h4>
                            </div>
                            <p id="view-descripcion" class="text-gray-700 leading-relaxed">Cargando...</p>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-center pt-6 border-t border-gray-200">
                            <button onclick="entrarModoEdicionPerfil()" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-3 rounded-xl hover:from-blue-700 hover:to-blue-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Editar Perfil
                            </button>
                        </div>
                    </div>

                    <!-- Modo Edición (oculto por defecto) -->
                    <div id="perfil-edit-mode" class="hidden space-y-6">
                        <div class="text-center mb-6">
                            <h4 class="text-2xl font-bold text-gray-800">Editar Información Empresarial</h4>
                            <p class="text-gray-600">Actualiza tu información de empresa</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                                <input type="text" id="perfil-nombre" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Número</label>
                                <input type="text" id="perfil-numero" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
                                <input type="text" id="perfil-direccion" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ciudad</label>
                                <input type="text" id="perfil-ciudad" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                                <input type="tel" id="perfil-telefono" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sitio Web</label>
                                <input type="url" id="perfil-sitio_web" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                            <textarea id="perfil-descripcion" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" placeholder="Describe tu empresa..."></textarea>
                        </div>

                        <!-- Botones de Acción en Modo Edición -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-center pt-6 border-t border-gray-200">
                            <button onclick="guardarPerfilEmpresa()" class="bg-gradient-to-r from-green-600 to-green-700 text-white px-8 py-3 rounded-xl hover:from-green-700 hover:to-green-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Guardar Cambios
                            </button>
                            <button onclick="cancelarEdicionPerfil()" class="bg-gray-500 text-white px-8 py-3 rounded-xl hover:bg-gray-600 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="hidden" id="empleados-section">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-10 py-8">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-4xl font-bold text-white">Gestión de Empleados</h2>
                            <p class="text-indigo-100 mt-2 text-lg">Administra el personal de tu empresa</p>
                        </div>
                        <button onclick="volverAInicio()" class="bg-white/20 backdrop-blur-sm text-white px-8 py-4 rounded-2xl hover:bg-white/30 transition duration-300 font-medium border border-white/30 flex items-center text-lg">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Volver
                        </button>
                    </div>
                </div>

                <div class="p-10">
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-800">Empleados Actuales</h3>
                        <button onclick="mostrarModalAsignarEmpleado()" class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-6 py-3 rounded-xl hover:from-indigo-700 hover:to-indigo-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Asignar Empleado
                        </button>
                    </div>

                    <div id="empleados-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Empleados se cargarán aquí -->
                    </div>

                    <div id="empleados-empty" class="hidden text-center py-20 bg-gradient-to-br from-indigo-50 to-blue-50 rounded-3xl">
                        <div class="text-indigo-400 text-8xl mb-6">👥</div>
                        <h4 class="text-2xl font-semibold text-gray-700 mb-4">No tienes empleados asignados</h4>
                        <p class="text-gray-600 mb-8 text-lg">¡Comienza a construir tu equipo!</p>
                        <button onclick="mostrarModalAsignarEmpleado()" class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-10 py-4 rounded-2xl hover:from-indigo-700 hover:to-indigo-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 text-lg">
                            Asignar Primer Empleado
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

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

    <!-- Modal para asignar empleado -->
    <div id="asignarEmpleadoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Asignar Nuevo Empleado</h3>
                <form id="asignarEmpleadoForm">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">ID del Usuario</label>
                        <input type="number" id="usuario_id" name="usuario_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <p class="text-xs text-gray-500 mt-1">Ingresa el ID del usuario que quieres asignar como empleado</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Rol</label>
                        <select id="rol" name="rol" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Seleccionar rol</option>
                            <option value="guia">Guía Turístico</option>
                            <option value="recepcionista">Recepcionista</option>
                            <option value="gerente">Gerente</option>
                            <option value="operador">Operador</option>
                        </select>
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="button" onclick="closeAsignarEmpleadoModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cancelar</button>
                        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Asignar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@push('scripts')
<script src="{{ asset('js/dashboard-empresa.js') }}"></script>
@endpush
@endsection
