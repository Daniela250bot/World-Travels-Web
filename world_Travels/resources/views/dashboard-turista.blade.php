@extends('components.dashboard-layout')

@section('title', 'Dashboard Turista - WORLD TRAVELS')

@section('nav-links')
    <a href="{{ route('search') }}" class="text-gray-700 hover:text-blue-600 transition">Buscar Actividades</a>
    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 transition">Inicio</a>
    <a href="{{ route('perfil') }}" class="text-gray-700 hover:text-blue-600 transition">Mi Perfil</a>
@endsection

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
@endpush

@push('scripts')
<script src="{{ asset('js/dashboard-turista.js') }}"></script>
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
@endpush

@section('hero')
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
            <button onclick="document.getElementById('reservas-proximas').scrollIntoView({ behavior: 'smooth' })" class="border-2 border-white text-white px-8 py-3 rounded-full font-semibold hover:bg-white hover:text-blue-600 transition duration-300">
                Ver Mis Reservas
            </button>
        </div>
    </div>
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" class="w-full h-12 fill-white">
            <path d="M0,32L48,37.3C96,43,192,53,288,58.7C384,64,480,64,576,58.7C672,53,768,43,864,48C960,53,1056,75,1152,80C1248,85,1344,75,1392,69.3L1440,64L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z"></path>
        </svg>
    </div>
</section>
@endsection

@section('content')
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
                        <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Acciones Rápidas
                    </h3>
                    <div class="space-y-4">
                        <button onclick="mostrarModalPublicacion()" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4 rounded-2xl hover:from-blue-700 hover:to-blue-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center text-base">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Nueva Publicación
                        </button>
                        <button onclick="scrollToEmpresas()" class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-4 rounded-2xl hover:from-green-700 hover:to-green-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center text-base">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Explorar Empresas
                        </button>
                        <button onclick="showSection('perfil')" class="w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-4 rounded-2xl hover:from-purple-700 hover:to-purple-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center text-base">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Mi Perfil
                        </button>
                    </div>
                </div>
            </div>

            <!-- Secciones Principales -->
            <div class="space-y-16">
                <!-- Mis Reservas -->
                <section class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-10 py-8">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-4xl font-bold text-white">Mis Reservas</h2>
                                <p class="text-blue-100 mt-2 text-lg">Gestiona tus experiencias turísticas</p>
                            </div>
                            <button onclick="showSection('reservas')" class="bg-white/20 backdrop-blur-sm text-white px-8 py-4 rounded-2xl hover:bg-white/30 transition duration-300 font-medium border border-white/30 text-lg">
                                Ver todas →
                            </button>
                        </div>
                    </div>

                    <div class="p-10">
                        <!-- Reservas Próximas -->
                        <div class="mb-12">
                            <div class="flex items-center mb-8">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-3xl font-bold text-gray-800">Próximas Experiencias</h3>
                            </div>
                            <div id="reservas-proximas" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                <!-- Reservas próximas se cargarán aquí -->
                            </div>
                            <div id="reservas-proximas-empty" class="hidden text-center py-20 bg-gray-50 rounded-3xl">
                                <div class="text-gray-400 text-8xl mb-6">🎯</div>
                                <h4 class="text-2xl font-semibold text-gray-700 mb-4">No tienes aventuras próximas</h4>
                                <p class="text-gray-600 mb-8 text-lg">¡Es hora de planificar tu próxima experiencia inolvidable!</p>
                                <button onclick="scrollToEmpresas()" class="bg-blue-600 text-white px-10 py-4 rounded-2xl hover:bg-blue-700 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 text-lg">
                                    Explorar Actividades
                                </button>
                            </div>
                        </div>

                        <!-- Reservas Pasadas -->
                        <div>
                            <div class="flex items-center mb-8">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-3xl font-bold text-gray-800">Experiencias Completadas</h3>
                            </div>
                            <div id="reservas-pasadas" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                <!-- Reservas pasadas se cargarán aquí -->
                            </div>
                            <div id="reservas-pasadas-empty" class="hidden text-center py-20 bg-gray-50 rounded-3xl">
                                <div class="text-gray-400 text-8xl mb-6">🏆</div>
                                <h4 class="text-2xl font-semibold text-gray-700 mb-4">Tu primera aventura te espera</h4>
                                <p class="text-gray-600 text-lg">Cada experiencia es una historia por contar</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Mis Publicaciones -->
                <section class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-10 py-8">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-4xl font-bold text-white">Mis Historias</h2>
                                <p class="text-purple-100 mt-2 text-lg">Comparte tus experiencias con la comunidad</p>
                            </div>
                            <button onclick="mostrarModalPublicacion()" class="bg-white/20 backdrop-blur-sm text-white px-8 py-4 rounded-2xl hover:bg-white/30 transition duration-300 font-medium border border-white/30 flex items-center text-lg">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Crear Historia
                            </button>
                        </div>
                    </div>

                    <div class="p-10">
                        <div id="publicaciones-list" class="space-y-10">
                            <!-- Las publicaciones se cargarán aquí -->
                        </div>

                        <div id="publicaciones-empty" class="hidden text-center py-20 bg-gradient-to-br from-purple-50 to-blue-50 rounded-3xl">
                            <div class="text-purple-400 text-8xl mb-6">📖</div>
                            <h4 class="text-2xl font-semibold text-gray-700 mb-4">Tu libro de aventuras está vacío</h4>
                            <p class="text-gray-600 mb-8 text-lg">¡Comparte tus experiencias y crea recuerdos inolvidables!</p>
                            <button onclick="mostrarModalPublicacion()" class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-10 py-4 rounded-2xl hover:from-purple-700 hover:to-purple-800 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 text-lg">
                                Comenzar a Escribir
                            </button>
                        </div>
                    </div>
                </section>

                <!-- Comunidad -->
                <section class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 px-10 py-8">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-4xl font-bold text-white">Comunidad WORLD TRAVELS</h2>
                                <p class="text-green-100 mt-2 text-lg">Descubre las historias de otros viajeros</p>
                            </div>
                            <button onclick="loadPublicacionesPublicas()" class="bg-white/20 backdrop-blur-sm text-white px-8 py-4 rounded-2xl hover:bg-white/30 transition duration-300 font-medium border border-white/30 flex items-center text-lg">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Actualizar
                            </button>
                        </div>
                    </div>

                    <div class="p-10">
                        <div id="publicaciones-publicas" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                            <!-- Publicaciones públicas se cargarán aquí -->
                        </div>

                        <div id="publicaciones-publicas-empty" class="hidden text-center py-20 bg-gradient-to-br from-green-50 to-blue-50 rounded-3xl">
                            <div class="text-green-400 text-8xl mb-6">🌍</div>
                            <h4 class="text-2xl font-semibold text-gray-700 mb-4">La comunidad está esperando tus historias</h4>
                            <p class="text-gray-600 text-lg">¡Sé el primero en compartir tus aventuras!</p>
                        </div>
                    </div>
                </section>
            </div>
        </div>

                <!-- Herramientas Interactivas -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Mapa Interactivo -->
                    <section class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-600 to-green-700 px-10 py-8">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="text-4xl font-bold text-white">Mapa Interactivo</h2>
                                    <p class="text-green-100 mt-2 text-lg">Descubre actividades cerca de ti</p>
                                </div>
                                <button onclick="centrarMapa()" class="bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-2xl hover:bg-white/30 transition duration-300 font-medium border border-white/30 flex items-center text-lg">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Centrar
                                </button>
                            </div>
                        </div>

                        <div class="p-10">
                            <div class="flex flex-wrap gap-6 mb-8">
                                <div class="flex items-center bg-blue-50 px-4 py-3 rounded-2xl">
                                    <input type="checkbox" id="filtro-empresa" checked class="mr-3 text-blue-600 w-4 h-4">
                                    <span class="text-base font-medium text-blue-700">Empresas</span>
                                </div>
                                <div class="flex items-center bg-purple-50 px-4 py-3 rounded-2xl">
                                    <input type="checkbox" id="filtro-admin" checked class="mr-3 text-purple-600 w-4 h-4">
                                    <span class="text-base font-medium text-purple-700">Viajes por Boyacá</span>
                                </div>
                                <select id="categoria-mapa" class="px-6 py-3 border border-gray-300 rounded-2xl text-base focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="">Todas las categorías</option>
                                </select>
                            </div>
                            <div id="mapa-container" class="h-96 w-full rounded-2xl overflow-hidden border border-gray-200">
                                <!-- Mapa se cargará aquí -->
                            </div>
                            <p class="text-base text-gray-600 mt-6 flex items-center">
                                <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Haz clic en los marcadores para ver detalles y reservar
                            </p>
                        </div>
                    </section>

                    <!-- Calendario de Actividades -->
                    <section class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-10 py-8">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="text-4xl font-bold text-white">Calendario</h2>
                                    <p class="text-blue-100 mt-2 text-lg">Planifica tus aventuras</p>
                                </div>
                                <div class="flex gap-3">
                                    <button onclick="cambiarVistaCalendario('month')" class="bg-white/20 backdrop-blur-sm text-white px-4 py-3 rounded-2xl hover:bg-white/30 transition duration-300 text-base font-medium">
                                        Mes
                                    </button>
                                    <button onclick="cambiarVistaCalendario('week')" class="bg-white/20 backdrop-blur-sm text-white px-4 py-3 rounded-2xl hover:bg-white/30 transition duration-300 text-base font-medium">
                                        Semana
                                    </button>
                                    <button onclick="cambiarVistaCalendario('day')" class="bg-white/20 backdrop-blur-sm text-white px-4 py-3 rounded-2xl hover:bg-white/30 transition duration-300 text-base font-medium">
                                        Día
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="p-10">
                            <div class="flex flex-wrap gap-6 mb-8">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-blue-500 rounded-full mr-3"></div>
                                    <span class="text-base text-gray-600 font-medium">Actividades</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-green-500 rounded-full mr-3"></div>
                                    <span class="text-base text-gray-600 font-medium">Festivos</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-purple-500 rounded-full mr-3"></div>
                                    <span class="text-base text-gray-600 font-medium">Mis Reservas</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-6 mb-8">
                                <select id="categoria-calendario" class="px-6 py-3 border border-gray-300 rounded-2xl text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Todas las categorías</option>
                                </select>
                                <label class="flex items-center bg-green-50 px-4 py-3 rounded-2xl">
                                    <input type="checkbox" id="mostrar-festivos" checked class="mr-3 text-green-600 w-4 h-4">
                                    <span class="text-base font-medium text-green-700">Festivos</span>
                                </label>
                                <label class="flex items-center bg-purple-50 px-4 py-3 rounded-2xl">
                                    <input type="checkbox" id="mostrar-reservas" checked class="mr-3 text-purple-600 w-4 h-4">
                                    <span class="text-base font-medium text-purple-700">Mis Reservas</span>
                                </label>
                            </div>

                            <div id="calendario-container" class="rounded-2xl overflow-hidden border border-gray-200">
                                <!-- Calendario se cargará aquí -->
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Explorar Experiencias -->
                <section class="space-y-12">
                    <!-- Empresas Destacadas -->
                    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-10 py-8">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="text-4xl font-bold text-white">Empresas Destacadas</h2>
                                    <p class="text-indigo-100 mt-2 text-lg">Descubre proveedores de experiencias únicas</p>
                                </div>
                                <select id="categoria-filter" class="px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 rounded-2xl text-white focus:ring-2 focus:ring-white/50 focus:border-white text-lg">
                                    <option value="" class="text-gray-800">Todas las categorías</option>
                                    <!-- Opciones se cargarán dinámicamente -->
                                </select>
                            </div>
                        </div>
                        <div class="p-10">
                            <div id="empresas-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                                <!-- Empresas se cargarán aquí -->
                            </div>
                        </div>
                    </div>

                    <!-- Viajes por el Boyacá -->
                    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-10 py-8">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="text-4xl font-bold text-white">Viajes por el Boyacá</h2>
                                    <p class="text-orange-100 mt-2 text-lg">Experiencias organizadas por la región</p>
                                </div>
                                <select id="viajes-categoria-filter" class="px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 rounded-2xl text-white focus:ring-2 focus:ring-white/50 focus:border-white text-lg">
                                    <option value="" class="text-gray-800">Todas las categorías</option>
                                    <!-- Opciones se cargarán dinámicamente -->
                                </select>
                            </div>
                        </div>
                        <div class="p-10">
                            <div id="viajes-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                                <!-- Actividades de administradores se cargarán aquí -->
                            </div>
                        </div>
                    </div>
                </section>


                <!-- Secciones Ocultas Mejoradas -->
                <div class="hidden" id="actividades-empresa-section">
                    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-10 py-8">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <button onclick="volverAEmpresas()" class="bg-white/20 backdrop-blur-sm text-white p-4 rounded-2xl hover:bg-white/30 transition duration-300 mr-6">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </button>
                                    <div>
                                        <h2 id="empresa-title" class="text-4xl font-bold text-white">Actividades de [Empresa]</h2>
                                        <p class="text-teal-100 mt-2 text-lg">Explora todas las experiencias disponibles</p>
                                    </div>
                                </div>
                                <select id="actividad-categoria-filter" class="px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 rounded-2xl text-white focus:ring-2 focus:ring-white/50 focus:border-white text-lg">
                                    <option value="" class="text-gray-800">Todas las categorías</option>
                                </select>
                            </div>
                        </div>
                        <div class="p-10">
                            <div id="actividades-empresa-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                                <!-- Actividades se cargarán aquí -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hidden" id="reservas-section">
                    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-cyan-600 to-cyan-700 px-10 py-8">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="text-4xl font-bold text-white">Todas Mis Reservas</h2>
                                    <p class="text-cyan-100 mt-2 text-lg">Historial completo de tus experiencias</p>
                                </div>
                                <button onclick="volverAInicio()" class="bg-white/20 backdrop-blur-sm text-white px-8 py-4 rounded-2xl hover:bg-white/30 transition duration-300 font-medium border border-white/30 flex items-center text-lg">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    Volver al Inicio
                                </button>
                            </div>
                        </div>
                        <div class="p-10">
                            <div id="reservas-list" class="space-y-8">
                                <!-- Reservas se cargarán aquí -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hidden" id="perfil-section">
                    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-pink-600 to-pink-700 px-8 py-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="text-3xl font-bold text-white">Mi Perfil</h2>
                                    <p class="text-pink-100 mt-1">Gestiona tu información personal</p>
                                </div>
                                <button onclick="volverAInicio()" class="bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-xl hover:bg-white/30 transition duration-300 font-medium border border-white/30 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    Volver al Inicio
                                </button>
                            </div>
                        </div>

                        <div class="p-8">
                            <!-- Perfil del Usuario - Vista Principal -->
                            <div class="max-w-5xl mx-auto">
                                <div class="bg-gradient-to-br from-pink-50 via-white to-purple-50 rounded-3xl shadow-xl border border-pink-100 overflow-hidden">
                                    <!-- Header del Perfil -->
                                    <div class="bg-gradient-to-r from-pink-500 to-purple-600 px-8 py-8 text-center">
                                        <div id="foto-perfil-container-view" class="relative inline-block mb-6">
                                            <img id="foto-perfil-preview-view" src="" alt="Foto de perfil"
                                                   class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-xl mx-auto">
                                            <div id="foto-perfil-placeholder-view" class="w-32 h-32 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-bold text-4xl mx-auto shadow-xl border-4 border-white/30">
                                                U
                                            </div>
                                        </div>
                                        <h3 id="perfil-nombre-completo" class="text-3xl font-bold text-white mb-2">Usuario</h3>
                                        <p class="text-lg text-pink-100">Turista - WORLD TRAVELS</p>
                                    </div>

                                    <div class="p-8">

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
                                    <p><span class="font-medium">Nacionalidad:</span> <span id="view-nacionalidad">Cargando...</span></p>
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800">Biografía</h4>
                            </div>
                            <p id="view-biografia" class="text-gray-700 leading-relaxed">Cargando...</p>
                        </div>

                        <div class="bg-white rounded-xl p-6 shadow-md border border-gray-100">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800">Privacidad</h4>
                            </div>
                            <p id="view-privacidad" class="text-gray-700">Cargando...</p>
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
                                Eliminar Perfil
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
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nacionalidad</label>
                            <input type="text" id="perfil-nacionalidad" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Biografía</label>
                            <textarea id="perfil-biografia" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" placeholder="Cuéntanos un poco sobre ti..."></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Privacidad del Perfil</label>
                            <select id="perfil-privacidad" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="publico">Público</option>
                                <option value="privado">Privado</option>
                            </select>
                        </div>

                        <!-- Gestión de Foto de Perfil -->
                        <div class="border-t pt-6">
                            <h5 class="text-lg font-semibold text-gray-800 mb-4">Foto de Perfil</h5>
                            <div class="flex flex-col sm:flex-row items-center gap-4">
                                <div id="foto-perfil-container" class="relative">
                                    <img id="foto-perfil-preview" src="" alt="Foto de perfil"
                                          class="w-24 h-24 rounded-full object-cover border-4 border-gray-200">
                                    <div id="foto-perfil-placeholder" class="w-24 h-24 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-bold text-xl">
                                        U
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
        </section>
@endsection

@section('modals')
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
                            <div class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-green-50 text-green-800 font-medium">
                                Confirmada ✓
                            </div>
                            <input type="hidden" name="Estado" value="confirmada">
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

    <!-- Modal de Nueva Publicación -->
    <div id="publicacion-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">Crear Nueva Publicación</h3>
                    <button onclick="closePublicacionModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="publicacion-form" enctype="multipart/form-data">
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Imagen</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition duration-300">
                            <div id="imagen-preview" class="mb-4">
                                <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500 text-4xl mx-auto mb-2">📷</div>
                                <p class="text-gray-600">Haz clic para seleccionar una imagen</p>
                            </div>
                            <input type="file" id="publicacion-imagen" name="imagen" accept="image/*" class="hidden" required>
                            <button type="button" onclick="document.getElementById('publicacion-imagen').click()"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                                Seleccionar Imagen
                            </button>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="publicacion-titulo" class="block text-gray-700 text-sm font-bold mb-2">Título</label>
                        <input type="text" id="publicacion-titulo" name="titulo" class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-blue-500" placeholder="Título de tu publicación" required>
                    </div>

                    <div class="mb-6">
                        <label for="publicacion-descripcion" class="block text-gray-700 text-sm font-bold mb-2">Descripción</label>
                        <textarea id="publicacion-descripcion" name="descripcion" rows="4" class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-blue-500 resize-none" placeholder="Cuéntanos sobre tu experiencia..." required></textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Privacidad</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="privacidad" value="publico" checked class="mr-2">
                                <span class="text-sm">Público</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="privacidad" value="privado" class="mr-2">
                                <span class="text-sm">Privado</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="button" onclick="closePublicacionModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition duration-300">
                            Cancelar
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition duration-300 transform hover:scale-105">
                            Publicar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

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

        // Función global para cargar reservas completas
        function loadReservasCompletas() {
            console.log('Cargando reservas completas...');
            const listSection = document.getElementById('reservas-list');

            if (!listSection) {
                console.error('Elemento reservas-list no encontrado');
                return;
            }

            showLoading(listSection, 'Cargando tus reservas...');

            fetchWithAuth('http://127.0.0.1:8000/api/turista/reservas')
            .then(response => {
                console.log('Respuesta de reservas:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Datos de reservas recibidos:', data);
                if (data.success) {
                    let html = '<h3 class="text-2xl font-bold mb-6 text-gray-800">Mis Reservas</h3>';

                    // Reservas próximas
                    if (data.reservas.proximas && data.reservas.proximas.length > 0) {
                        html += '<h4 class="text-xl font-semibold mb-4 text-blue-600">Próximas</h4>';
                        html += '<div class="space-y-4 mb-8">';
                        data.reservas.proximas.forEach(reserva => {
                            html += createReservaHTML(reserva);
                        });
                        html += '</div>';
                    }

                    // Reservas pasadas
                    if (data.reservas.pasadas && data.reservas.pasadas.length > 0) {
                        html += '<h4 class="text-xl font-semibold mb-4 text-green-600">Pasadas</h4>';
                        html += '<div class="space-y-4">';
                        data.reservas.pasadas.forEach(reserva => {
                            html += createReservaHTML(reserva);
                        });
                        html += '</div>';
                    }

                    if ((!data.reservas.pasadas || data.reservas.pasadas.length === 0) &&
                        (!data.reservas.proximas || data.reservas.proximas.length === 0)) {
                        html += '<div class="text-center py-12"><div class="text-gray-400 text-6xl mb-4">📅</div><p class="text-gray-600 text-lg">No tienes reservas registradas</p><p class="text-gray-500 mt-2">¡Explora nuestras actividades y reserva tu próxima aventura!</p></div>';
                    }

                    listSection.innerHTML = html;
                } else {
                    console.error('Respuesta no exitosa:', data);
                    showError(listSection, 'Error al cargar reservas');
                }
            })
            .catch(error => {
                console.error('Error cargando reservas:', error);
                showError(listSection, 'Error al cargar tus reservas');
            });
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
            loadReservasOverview(); // Cargar vista previa de reservas en pantalla principal
            loadPublicaciones(); // Cargar publicaciones del usuario
            loadPublicacionesPublicas(); // Cargar publicaciones públicas de otros usuarios
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

            // Usar la API de estadísticas del turista
            console.log('Haciendo fetch a /api/turista/estadisticas...');
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
                    <div class="bg-purple-50 p-6 rounded-lg text-center">
                        <h3 class="text-2xl font-bold text-purple-600">0</h3>
                        <p class="text-gray-600">Próximas</p>
                    </div>
                `;
            });
        }

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
                    // Mostrar todas las reservas próximas
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

                    // Mostrar todas las reservas pasadas
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
                            ${new Date(reserva.Fecha_Reserva).toLocaleDateString('es-ES')}
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

        // ==================== EMPRESAS ====================
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

                    // Animación de entrada para las empresas
                    renderEmpresas(empresas, empresasList);
                    setTimeout(() => {
                        const cards = empresasList.querySelectorAll('.empresa-card');
                        cards.forEach((card, index) => {
                            setTimeout(() => {
                                card.style.opacity = '0';
                                card.style.transform = 'translateY(20px)';
                                card.style.transition = 'all 0.3s ease-out';
                                card.style.opacity = '1';
                                card.style.transform = 'translateY(0)';
                            }, index * 100);
                        });
                    }, 100);
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
            // Usar el ID del usuario de la tabla users (que será convertido automáticamente por el backend)
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

                    // Agregar event listener al formulario inmediatamente
                    const reservaForm = document.getElementById('reserva-form');
                    if (reservaForm) {
                        reservaForm.addEventListener('submit', function(e) {
                            e.preventDefault();
                            handleReservaSubmit(e);
                        });
                    }
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

        // ==================== FUNCIONES PARA PUBLICACIONES ====================
        function mostrarModalPublicacion() {
            document.getElementById('publicacion-modal').classList.remove('hidden');
        }

        function closePublicacionModal() {
            document.getElementById('publicacion-modal').classList.add('hidden');
            // Limpiar formulario
            document.getElementById('publicacion-form').reset();
            document.getElementById('imagen-preview').innerHTML = `
                <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500 text-4xl mx-auto mb-2">📷</div>
                <p class="text-gray-600">Haz clic para seleccionar una imagen</p>
            `;
        }

        // Vista previa de imagen
        document.addEventListener('DOMContentLoaded', function() {
            const imagenInput = document.getElementById('publicacion-imagen');
            if (imagenInput) {
                imagenInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('imagen-preview').innerHTML = `
                                <img src="${e.target.result}" class="w-32 h-32 object-cover rounded-lg mx-auto">
                            `;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });

        // Crear publicación
        document.addEventListener('DOMContentLoaded', function() {
            const publicacionForm = document.getElementById('publicacion-form');
            if (publicacionForm) {
                publicacionForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    crearPublicacion();
                });
            }
        });

        function crearPublicacion() {
            const formData = new FormData(document.getElementById('publicacion-form'));
            const submitBtn = document.querySelector('#publicacion-form button[type="submit"]');
            const originalText = submitBtn.textContent;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mx-auto"></div>';

            fetchWithAuth('http://127.0.0.1:8000/api/turista/publicaciones', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Publicación creada exitosamente', 'success');
                    closePublicacionModal();
                    loadPublicaciones(); // Recargar lista de publicaciones
                } else {
                    showNotification(data.message || 'Error al crear publicación', 'error');
                }
            })
            .catch(error => {
                console.error('Error creando publicación:', error);
                showNotification('Error al crear la publicación', 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        }

        function loadPublicaciones() {
            const container = document.getElementById('publicaciones-list');
            const emptyState = document.getElementById('publicaciones-empty');

            container.innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div><p class="mt-2 text-gray-600">Cargando publicaciones...</p></div>';

            fetchWithAuth('http://127.0.0.1:8000/api/turista/publicaciones')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.publicaciones.length === 0) {
                        container.innerHTML = '';
                        emptyState.classList.remove('hidden');
                        return;
                    }

                    emptyState.classList.add('hidden');
                    container.innerHTML = '';

                    data.publicaciones.forEach(publicacion => {
                        const publicacionCard = createPublicacionCard(publicacion);
                        container.appendChild(publicacionCard);
                    });
                }
            })
            .catch(error => {
                console.error('Error cargando publicaciones:', error);
                container.innerHTML = '<div class="text-center py-8"><p class="text-red-500">Error al cargar publicaciones</p></div>';
            });
        }

        function createPublicacionCard(publicacion) {
            const div = document.createElement('div');
            div.className = 'bg-white rounded-2xl shadow-lg overflow-hidden';

            const fecha = new Date(publicacion.fecha_creacion).toLocaleDateString('es-ES', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            div.innerHTML = `
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <img src="${publicacion.usuario.foto_perfil || '/default-avatar.png'}"
                                 alt="${publicacion.usuario.name}"
                                 class="w-10 h-10 rounded-full object-cover mr-3">
                            <div>
                                <h4 class="font-semibold text-gray-800">${publicacion.usuario.name}</h4>
                                <p class="text-sm text-gray-500">${fecha}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-1 rounded-full text-xs font-medium ${publicacion.privacidad === 'publico' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                                ${publicacion.privacidad === 'publico' ? '🌍 Público' : '🔒 Privado'}
                            </span>
                        </div>
                    </div>

                    ${publicacion.imagen ? `
                        <div class="mb-4 rounded-lg overflow-hidden">
                            <img src="${publicacion.imagen}" alt="${publicacion.titulo}" class="w-full h-64 object-cover">
                        </div>
                    ` : ''}

                    <h3 class="text-xl font-bold text-gray-800 mb-2">${publicacion.titulo}</h3>
                    <p class="text-gray-600 mb-4 leading-relaxed">${publicacion.descripcion}</p>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <div class="flex items-center space-x-4">
                            <button onclick="darLikePublicacion(${publicacion.id})" class="flex items-center space-x-1 text-gray-600 hover:text-red-500 transition duration-300">
                                <svg class="w-5 h-5 ${publicacion.user_liked ? 'fill-current text-red-500' : ''}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span>${publicacion.likes_count || 0}</span>
                            </button>
                            <button onclick="mostrarComentariosPublicacion(${publicacion.id})" class="flex items-center space-x-1 text-gray-600 hover:text-blue-500 transition duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <span>${publicacion.comentarios_count || 0}</span>
                            </button>
                        </div>
                        <button onclick="eliminarPublicacion(${publicacion.id})" class="text-red-500 hover:text-red-700 transition duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `;

            return div;
        }

        function darLikePublicacion(publicacionId) {
            fetchWithAuth(`http://127.0.0.1:8000/api/turista/publicaciones/${publicacionId}/like`, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadPublicaciones(); // Recargar para actualizar likes
                }
            })
            .catch(error => console.error('Error dando like:', error));
        }

        function eliminarPublicacion(publicacionId) {
            if (!confirm('¿Estás seguro de que quieres eliminar esta publicación?')) {
                return;
            }

            fetchWithAuth(`http://127.0.0.1:8000/api/turista/publicaciones/${publicacionId}`, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Publicación eliminada', 'success');
                    loadPublicaciones();
                } else {
                    showNotification('Error al eliminar publicación', 'error');
                }
            })
            .catch(error => console.error('Error eliminando publicación:', error));
        }

        function loadPublicacionesPublicas() {
            const container = document.getElementById('publicaciones-publicas');
            const emptyState = document.getElementById('publicaciones-publicas-empty');

            container.innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div><p class="mt-2 text-gray-600">Cargando publicaciones...</p></div>';

            fetchWithAuth('http://127.0.0.1:8000/api/turista/publicaciones-publicas')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.publicaciones.length === 0) {
                        container.innerHTML = '';
                        emptyState.classList.remove('hidden');
                        return;
                    }

                    emptyState.classList.add('hidden');
                    container.innerHTML = '';

                    data.publicaciones.forEach(publicacion => {
                        const publicacionCard = createPublicacionPublicaCard(publicacion);
                        container.appendChild(publicacionCard);
                    });
                } else {
                    container.innerHTML = '<div class="text-center py-8"><p class="text-red-500">Error al cargar publicaciones públicas</p></div>';
                }
            })
            .catch(error => {
                console.error('Error cargando publicaciones públicas:', error);
                container.innerHTML = '<div class="text-center py-8"><p class="text-red-500">Error al cargar publicaciones públicas</p></div>';
            });
        }

        function createPublicacionPublicaCard(publicacion) {
            const div = document.createElement('div');
            div.className = 'bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300';

            const fecha = new Date(publicacion.fecha_creacion).toLocaleDateString('es-ES', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            div.innerHTML = `
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <img src="${publicacion.usuario.foto_perfil || '/default-avatar.png'}"
                                 alt="${publicacion.usuario.name}"
                                 class="w-10 h-10 rounded-full object-cover mr-3">
                            <div>
                                <h4 class="font-semibold text-gray-800">${publicacion.usuario.name}</h4>
                                <p class="text-sm text-gray-500">${fecha}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                🌍 Público
                            </span>
                        </div>
                    </div>

                    ${publicacion.imagen ? `
                        <div class="mb-4 rounded-lg overflow-hidden">
                            <img src="${publicacion.imagen}" alt="${publicacion.titulo}" class="w-full h-48 object-cover">
                        </div>
                    ` : ''}

                    <h3 class="text-xl font-bold text-gray-800 mb-2">${publicacion.titulo}</h3>
                    <p class="text-gray-600 mb-4 leading-relaxed">${publicacion.descripcion}</p>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <div class="flex items-center space-x-4">
                            <button onclick="darLikePublicacionPublica(${publicacion.id})" class="flex items-center space-x-1 text-gray-600 hover:text-red-500 transition duration-300">
                                <svg class="w-5 h-5 ${publicacion.user_liked ? 'fill-current text-red-500' : ''}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span>${publicacion.likes_count || 0}</span>
                            </button>
                            <button onclick="mostrarComentariosPublicacionPublica(${publicacion.id})" class="flex items-center space-x-1 text-gray-600 hover:text-blue-500 transition duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <span>${publicacion.comentarios_count || 0}</span>
                            </button>
                        </div>
                    </div>
                </div>
            `;

            return div;
        }

        function darLikePublicacionPublica(publicacionId) {
            fetchWithAuth(`http://127.0.0.1:8000/api/turista/publicaciones/${publicacionId}/like`, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadPublicacionesPublicas(); // Recargar para actualizar likes
                }
            })
            .catch(error => console.error('Error dando like:', error));
        }

        function mostrarComentariosPublicacionPublica(publicacionId) {
            // Abrir modal de comentarios para publicaciones públicas
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4';
            modal.id = 'comentarios-publicos-modal';

            modal.innerHTML = `
                <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col">
                    <div class="p-6 border-b">
                        <div class="flex justify-between items-center">
                            <h3 class="text-2xl font-bold text-gray-800">Comentarios</h3>
                            <button onclick="closeComentariosPublicosModal()" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div id="comentarios-publicos-list" class="flex-1 overflow-y-auto p-6 space-y-4">
                        <!-- Comentarios se cargarán aquí -->
                        <div class="text-center py-8">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                            <p class="mt-2 text-gray-600">Cargando comentarios...</p>
                        </div>
                    </div>

                    <div class="p-6 border-t bg-gray-50">
                        <form id="comentario-publico-form" class="flex space-x-3">
                            <input type="hidden" id="publicacion-publica-id-comentario" value="${publicacionId}">
                            <input type="text" id="nuevo-comentario-publico" placeholder="Escribe un comentario..."
                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   maxlength="500" required>
                            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300 transform hover:scale-105">
                                Comentar
                            </button>
                        </form>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);
            modal.classList.remove('hidden');

            // Cargar comentarios
            cargarComentariosPublicos(publicacionId);

            // Manejar envío del formulario
            document.getElementById('comentario-publico-form').addEventListener('submit', function(e) {
                e.preventDefault();
                crearComentarioPublico(publicacionId);
            });
        }

        function closeComentariosPublicosModal() {
            const modal = document.getElementById('comentarios-publicos-modal');
            if (modal) {
                modal.remove();
            }
        }

        function cargarComentariosPublicos(publicacionId) {
            const container = document.getElementById('comentarios-publicos-list');

            fetchWithAuth(`http://127.0.0.1:8000/api/turista/publicaciones/${publicacionId}/comentarios`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.comentarios.length === 0) {
                        container.innerHTML = '<div class="text-center py-8"><p class="text-gray-500">Aún no hay comentarios. ¡Sé el primero en comentar!</p></div>';
                        return;
                    }

                    container.innerHTML = '';
                    data.comentarios.forEach(comentario => {
                        const comentarioElement = createComentarioElement(comentario);
                        container.appendChild(comentarioElement);
                    });
                } else {
                    container.innerHTML = '<div class="text-center py-8"><p class="text-red-500">Error al cargar comentarios</p></div>';
                }
            })
            .catch(error => {
                console.error('Error cargando comentarios:', error);
                container.innerHTML = '<div class="text-center py-8"><p class="text-red-500">Error al cargar comentarios</p></div>';
            });
        }

        function crearComentarioPublico(publicacionId) {
            const comentarioInput = document.getElementById('nuevo-comentario-publico');
            const comentario = comentarioInput.value.trim();

            if (!comentario) {
                showNotification('Por favor escribe un comentario', 'warning');
                return;
            }

            const submitBtn = document.querySelector('#comentario-publico-form button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mx-auto"></div>';

            fetchWithAuth(`http://127.0.0.1:8000/api/turista/publicaciones/${publicacionId}/comentarios`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ comentario: comentario })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Comentario agregado exitosamente', 'success');
                    comentarioInput.value = '';
                    cargarComentariosPublicos(publicacionId); // Recargar comentarios
                    loadPublicacionesPublicas(); // Actualizar contador de comentarios
                } else {
                    showNotification(data.message || 'Error al agregar comentario', 'error');
                }
            })
            .catch(error => {
                console.error('Error creando comentario:', error);
                showNotification('Error al agregar comentario', 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        }

        function mostrarComentariosPublicacion(publicacionId) {
            // Abrir modal de comentarios
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4';
            modal.id = 'comentarios-modal';

            modal.innerHTML = `
                <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col">
                    <div class="p-6 border-b">
                        <div class="flex justify-between items-center">
                            <h3 class="text-2xl font-bold text-gray-800">Comentarios</h3>
                            <button onclick="closeComentariosModal()" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div id="comentarios-list" class="flex-1 overflow-y-auto p-6 space-y-4">
                        <!-- Comentarios se cargarán aquí -->
                        <div class="text-center py-8">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                            <p class="mt-2 text-gray-600">Cargando comentarios...</p>
                        </div>
                    </div>

                    <div class="p-6 border-t bg-gray-50">
                        <form id="comentario-form" class="flex space-x-3">
                            <input type="hidden" id="publicacion-id-comentario" value="${publicacionId}">
                            <input type="text" id="nuevo-comentario" placeholder="Escribe un comentario..."
                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   maxlength="500" required>
                            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300 transform hover:scale-105">
                                Comentar
                            </button>
                        </form>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);
            modal.classList.remove('hidden');

            // Cargar comentarios
            cargarComentarios(publicacionId);

            // Manejar envío del formulario
            document.getElementById('comentario-form').addEventListener('submit', function(e) {
                e.preventDefault();
                crearComentario(publicacionId);
            });
        }

        function closeComentariosModal() {
            const modal = document.getElementById('comentarios-modal');
            if (modal) {
                modal.remove();
            }
        }

        function cargarComentarios(publicacionId) {
            const container = document.getElementById('comentarios-list');

            fetchWithAuth(`http://127.0.0.1:8000/api/turista/publicaciones/${publicacionId}/comentarios`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.comentarios.length === 0) {
                        container.innerHTML = '<div class="text-center py-8"><p class="text-gray-500">Aún no hay comentarios. ¡Sé el primero en comentar!</p></div>';
                        return;
                    }

                    container.innerHTML = '';
                    data.comentarios.forEach(comentario => {
                        const comentarioElement = createComentarioElement(comentario);
                        container.appendChild(comentarioElement);
                    });
                } else {
                    container.innerHTML = '<div class="text-center py-8"><p class="text-red-500">Error al cargar comentarios</p></div>';
                }
            })
            .catch(error => {
                console.error('Error cargando comentarios:', error);
                container.innerHTML = '<div class="text-center py-8"><p class="text-red-500">Error al cargar comentarios</p></div>';
            });
        }

        function createComentarioElement(comentario) {
            const div = document.createElement('div');
            div.className = 'bg-gray-50 rounded-lg p-4';

            const fecha = new Date(comentario.fecha_creacion).toLocaleDateString('es-ES', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            div.innerHTML = `
                <div class="flex items-start space-x-3">
                    <img src="${comentario.usuario.foto_perfil || '/default-avatar.png'}"
                         alt="${comentario.usuario.name}"
                         class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <h4 class="font-semibold text-gray-800 text-sm">${comentario.usuario.name}</h4>
                            <span class="text-xs text-gray-500">${fecha}</span>
                        </div>
                        <p class="text-gray-700 mt-1 text-sm leading-relaxed">${comentario.comentario}</p>
                    </div>
                </div>
            `;

            return div;
        }

        function crearComentario(publicacionId) {
            const comentarioInput = document.getElementById('nuevo-comentario');
            const comentario = comentarioInput.value.trim();

            if (!comentario) {
                showNotification('Por favor escribe un comentario', 'warning');
                return;
            }

            const submitBtn = document.querySelector('#comentario-form button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mx-auto"></div>';

            fetchWithAuth(`http://127.0.0.1:8000/api/turista/publicaciones/${publicacionId}/comentarios`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ comentario: comentario })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Comentario agregado exitosamente', 'success');
                    comentarioInput.value = '';
                    cargarComentarios(publicacionId); // Recargar comentarios
                    loadPublicaciones(); // Actualizar contador de comentarios
                } else {
                    showNotification(data.message || 'Error al agregar comentario', 'error');
                }
            })
            .catch(error => {
                console.error('Error creando comentario:', error);
                showNotification('Error al agregar comentario', 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
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
                    loadReservasOverview(); // Recargar vista previa de reservas en pantalla principal
                    // Si estamos en la sección de reservas, actualizar la lista
                    if (!document.getElementById('reservas-section').classList.contains('hidden')) {
                        loadReservasCompletas();
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
            // Ocultar todas las secciones existentes con animación
            const allSections = ['empresas-section', 'actividades-empresa-section', 'reservas-section', 'perfil-section'];

            allSections.forEach(sectionId => {
                const sectionElement = document.getElementById(sectionId);
                if (sectionElement && !sectionElement.classList.contains('hidden')) {
                    fadeOut(sectionElement, 200);
                }
            });

            // Mostrar la sección seleccionada con animación
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

                // Scroll suave a la sección después de mostrarla
                setTimeout(() => {
                    const selectedSection = document.getElementById(section + '-section');
                    if (selectedSection) {
                        smoothScrollToTop();
                    }
                }, 100);
            }, 200);

            // Cargar contenido según la sección
            switch(section) {
                case 'empresas':
                    // Las empresas ya están cargadas inicialmente
                    break;
                case 'actividades-empresa':
                    // Las actividades se cargan al hacer clic en una empresa
                    break;
                case 'reservas':
                    setTimeout(() => loadReservasCompletas(), 300); // Carga todas las reservas en la sección dedicada
                    break;
                case 'perfil':
                    setTimeout(() => loadPerfil(), 300);
                    break;
            }
        }

        function loadPerfil() {
            console.log('Cargando perfil del usuario...');

            // Cargar información del perfil
            fetchWithAuth('http://127.0.0.1:8000/api/me')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.usuario) {
                    const usuario = data.usuario;

                    // Llenar modo vista
                    document.getElementById('view-nombre').textContent = usuario.Nombre || 'No especificado';
                    document.getElementById('view-apellido').textContent = usuario.Apellido || 'No especificado';
                    document.getElementById('view-email').textContent = usuario.Email || 'No especificado';
                    document.getElementById('view-telefono').textContent = usuario.Telefono || 'No especificado';
                    document.getElementById('view-nacionalidad').textContent = usuario.Nacionalidad || 'No especificada';
                    document.getElementById('view-biografia').textContent = usuario.biografia || 'Sin biografía';
                    document.getElementById('view-privacidad').textContent = usuario.privacidad_perfil === 'publico' ? 'Perfil Público' : 'Perfil Privado';

                    // Nombre completo en el header
                    const nombreCompleto = `${usuario.Nombre || ''} ${usuario.Apellido || ''}`.trim() || 'Usuario';
                    document.getElementById('perfil-nombre-completo').textContent = nombreCompleto;

                    // Llenar modo edición
                    document.getElementById('perfil-nombre').value = usuario.Nombre || '';
                    document.getElementById('perfil-apellido').value = usuario.Apellido || '';
                    document.getElementById('perfil-email').value = usuario.Email || '';
                    document.getElementById('perfil-telefono').value = usuario.Telefono || '';
                    document.getElementById('perfil-nacionalidad').value = usuario.Nacionalidad || '';
                    document.getElementById('perfil-biografia').value = usuario.biografia || '';
                    document.getElementById('perfil-privacidad').value = usuario.privacidad_perfil || 'publico';

                    // Mostrar foto de perfil
                    updateFotoPerfilPreview(usuario.foto_perfil, usuario.name || usuario.Nombre);
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

            const inicial = nombre ? nombre.charAt(0).toUpperCase() : 'U';

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
            const biografia = document.getElementById('perfil-biografia').value;
            const privacidad = document.getElementById('perfil-privacidad').value;
            const telefono = document.getElementById('perfil-telefono').value;

            const data = {
                biografia: biografia,
                privacidad_perfil: privacidad,
                telefono: telefono
            };

            fetchWithAuth('http://127.0.0.1:8000/api/turista/perfil', {
                method: 'POST',
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
            if (!confirm('¿Estás completamente seguro de que quieres eliminar tu perfil?\n\nEsta acción no se puede deshacer y perderás:\n- Todas tus reservas\n- Todas tus publicaciones\n- Tu acceso a la plataforma\n\n¿Deseas continuar?')) {
                return;
            }

            if (!confirm('Última confirmación: ¿Realmente quieres eliminar permanentemente tu perfil de WORLD TRAVELS?')) {
                return;
            }

            fetchWithAuth('http://127.0.0.1:8000/api/turista/perfil', {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Perfil eliminado exitosamente. Serás redirigido al inicio.', 'success');
                    // Limpiar token y redirigir
                    setTimeout(() => {
                        localStorage.removeItem('token');
                        window.location.href = '{{ route("home") }}';
                    }, 2000);
                } else {
                    showNotification(data.message || 'Error al eliminar perfil', 'error');
                }
            })
            .catch(error => {
                console.error('Error eliminando perfil:', error);
                showNotification('Error al eliminar el perfil', 'error');
            });
        }

        // Manejar cambio de foto de perfil
        document.addEventListener('DOMContentLoaded', function() {
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

        function subirFotoPerfil(file) {
            const formData = new FormData();
            formData.append('foto_perfil', file);

            // Mostrar loading
            const btnCambiar = document.querySelector('button[onclick*="foto-perfil-input"]');
            const originalText = btnCambiar.textContent;
            btnCambiar.disabled = true;
            btnCambiar.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-gray-700 mx-auto"></div>';

            fetchWithAuth('http://127.0.0.1:8000/api/turista/foto-perfil', {
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

            fetchWithAuth('http://127.0.0.1:8000/api/turista/foto-perfil', {
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
            .catch(error => {
                console.error('Error eliminando foto:', error);
                showNotification('Error al eliminar la foto de perfil', 'error');
            });
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
                            loadReservasCompletas();
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
                    loadReservasCompletas();
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
                        loadReservasCompletas();
                    } else {
                        showNotification('Error: ' + (data.message || 'Error desconocido'), 'error');
                    }
                })
                .catch(error => console.error('Error agregando reseña:', error));
            };
        }

        // ==================== MAPA INTERACTIVO ====================
        let mapa = null;
        let marcadores = [];

        function inicializarMapa() {
            console.log('Inicializando mapa...');

            const container = document.getElementById('mapa-container');
            if (!container) {
                console.error('Contenedor del mapa no encontrado');
                return;
            }

            // Coordenadas aproximadas del centro de Boyacá, Colombia
            const boyacaCenter = [5.5353, -73.3678];

            // Inicializar mapa
            mapa = L.map('mapa-container').setView(boyacaCenter, 9);

            // Agregar capa de mapa base (OpenStreetMap)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 18,
            }).addTo(mapa);

            // Cargar actividades en el mapa
            cargarActividadesMapa();

            // Agregar event listeners para filtros
            document.getElementById('filtro-empresa').addEventListener('change', filtrarMarcadores);
            document.getElementById('filtro-admin').addEventListener('change', filtrarMarcadores);
            document.getElementById('categoria-mapa').addEventListener('change', filtrarMarcadores);
        }

        function centrarMapa() {
            if (mapa) {
                mapa.setView([5.5353, -73.3678], 9);
            }
        }

        function cargarActividadesMapa() {
            console.log('Cargando actividades para el mapa...');

            // Cargar actividades de empresas
            fetch('http://127.0.0.1:8000/api/listarActividades')
                .then(response => response.json())
                .then(data => {
                    console.log('Actividades de empresas:', data);
                    agregarMarcadores(data, 'empresa');
                })
                .catch(error => console.error('Error cargando actividades de empresas:', error));

            // Cargar actividades administrativas
            fetch('http://127.0.0.1:8000/api/listarActividades?admin=1')
                .then(response => response.json())
                .then(data => {
                    console.log('Actividades administrativas:', data);
                    agregarMarcadores(data, 'admin');
                })
                .catch(error => console.error('Error cargando actividades administrativas:', error));
        }

        function agregarMarcadores(actividades, tipo) {
            actividades.forEach(actividad => {
                // Usar coordenadas aproximadas si no están disponibles
                // Aquí podrías agregar lógica para geocodificar direcciones
                let lat = 5.5353 + (Math.random() - 0.5) * 2; // Coordenadas aleatorias cerca de Boyacá
                let lng = -73.3678 + (Math.random() - 0.5) * 2;

                const marker = L.marker([lat, lng], {
                    tipo: tipo,
                    categoria: actividad.categoria?.nombre || 'Sin categoría'
                });

                const popupContent = `
                    <div class="max-w-xs">
                        <h3 class="font-bold text-lg mb-2">${actividad.Nombre_Actividad}</h3>
                        <img src="${actividad.Imagen || 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80'}"
                             alt="${actividad.Nombre_Actividad}" class="w-full h-24 object-cover rounded mb-2">
                        <p class="text-sm text-gray-600 mb-2">${actividad.Descripcion}</p>
                        <div class="text-sm mb-2">
                            <strong>Fecha:</strong> ${new Date(actividad.Fecha_Actividad).toLocaleDateString('es-ES')}<br>
                            <strong>Hora:</strong> ${actividad.Hora_Actividad}<br>
                            <strong>Precio:</strong> $${actividad.Precio}<br>
                            <strong>Cupo:</strong> ${actividad.Cupo_Maximo} personas
                        </div>
                        <button onclick="openReservaModal(${actividad.id}, '${actividad.Nombre_Actividad}')"
                                class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition">
                            Reservar
                        </button>
                    </div>
                `;

                marker.bindPopup(popupContent);
                marcadores.push(marker);
            });

            // Aplicar filtros iniciales
            filtrarMarcadores();
        }

        function filtrarMarcadores() {
            const filtroEmpresa = document.getElementById('filtro-empresa').checked;
            const filtroAdmin = document.getElementById('filtro-admin').checked;
            const categoriaSeleccionada = document.getElementById('categoria-mapa').value;

            marcadores.forEach(marker => {
                const mostrarTipo = (marker.options.tipo === 'empresa' && filtroEmpresa) ||
                                   (marker.options.tipo === 'admin' && filtroAdmin);
                const mostrarCategoria = !categoriaSeleccionada || marker.options.categoria === categoriaSeleccionada;

                if (mostrarTipo && mostrarCategoria) {
                    if (!mapa.hasLayer(marker)) {
                        marker.addTo(mapa);
                    }
                } else {
                    if (mapa.hasLayer(marker)) {
                        mapa.removeLayer(marker);
                    }
                }
            });
        }

        // ==================== CALENDARIO ====================
        let calendario = null;

        function inicializarCalendario() {
            console.log('Inicializando calendario...');

            const container = document.getElementById('calendario-container');
            if (!container) {
                console.error('Contenedor del calendario no encontrado');
                return;
            }

            const calendarEl = document.createElement('div');
            calendarEl.id = 'calendario';
            container.appendChild(calendarEl);

            calendario = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    cargarEventosCalendario(fetchInfo, successCallback, failureCallback);
                },
                eventClick: function(info) {
                    mostrarDetallesEvento(info.event);
                },
                height: 'auto',
                dayMaxEvents: 3,
                moreLinkClick: 'popover'
            });

            calendario.render();

            // Agregar event listeners para filtros
            document.getElementById('categoria-calendario').addEventListener('change', () => calendario.refetchEvents());
            document.getElementById('mostrar-festivos').addEventListener('change', () => calendario.refetchEvents());
            document.getElementById('mostrar-reservas').addEventListener('change', () => calendario.refetchEvents());
        }

        function cambiarVistaCalendario(vista) {
            if (calendario) {
                let vistaFullCalendar = 'dayGridMonth';
                switch(vista) {
                    case 'month': vistaFullCalendar = 'dayGridMonth'; break;
                    case 'week': vistaFullCalendar = 'timeGridWeek'; break;
                    case 'day': vistaFullCalendar = 'timeGridDay'; break;
                }
                calendario.changeView(vistaFullCalendar);
            }
        }

        function cargarEventosCalendario(fetchInfo, successCallback, failureCallback) {
            const eventos = [];

            // Cargar días festivos
            if (document.getElementById('mostrar-festivos').checked) {
                // Aquí cargarías días festivos desde la API
                // Por ahora, algunos días festivos de Colombia
                const diasFestivos = [
                    { fecha: '2025-01-01', nombre: 'Año Nuevo' },
                    { fecha: '2025-01-06', nombre: 'Día de Reyes' },
                    { fecha: '2025-03-24', nombre: 'Día de San José' },
                    { fecha: '2025-04-13', nombre: 'Domingo de Ramos' },
                    { fecha: '2025-04-18', nombre: 'Viernes Santo' },
                    { fecha: '2025-05-01', nombre: 'Día del Trabajo' },
                    { fecha: '2025-05-29', nombre: 'Ascensión del Señor' },
                    { fecha: '2025-06-08', nombre: 'Corpus Christi' },
                    { fecha: '2025-06-23', nombre: 'Sagrado Corazón' },
                    { fecha: '2025-07-20', nombre: 'Día de la Independencia' },
                    { fecha: '2025-08-07', nombre: 'Batalla de Boyacá' },
                    { fecha: '2025-08-18', nombre: 'Asunción de la Virgen' },
                    { fecha: '2025-10-13', nombre: 'Día de la Raza' },
                    { fecha: '2025-11-03', nombre: 'Todos los Santos' },
                    { fecha: '2025-11-17', nombre: 'Independencia de Cartagena' },
                    { fecha: '2025-12-08', nombre: 'Día de la Inmaculada Concepción' },
                    { fecha: '2025-12-25', nombre: 'Navidad' }
                ];

                diasFestivos.forEach(festivo => {
                    eventos.push({
                        title: festivo.nombre,
                        start: festivo.fecha,
                        backgroundColor: '#10B981',
                        borderColor: '#10B981',
                        textColor: '#FFFFFF',
                        tipo: 'festivo'
                    });
                });
            }

            // Cargar actividades
            const categoriaSeleccionada = document.getElementById('categoria-calendario').value;

            fetch(`http://127.0.0.1:8000/api/listarActividades${categoriaSeleccionada ? `?categoria=${categoriaSeleccionada}` : ''}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(actividad => {
                        eventos.push({
                            title: actividad.Nombre_Actividad,
                            start: actividad.Fecha_Actividad,
                            backgroundColor: '#3B82F6',
                            borderColor: '#3B82F6',
                            textColor: '#FFFFFF',
                            tipo: 'actividad',
                            extendedProps: {
                                actividad: actividad
                            }
                        });
                    });

                    // Cargar reservas del usuario
                    if (document.getElementById('mostrar-reservas').checked) {
                        fetchWithAuth('http://127.0.0.1:8000/api/turista/reservas')
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    const todasReservas = [...(data.reservas.proximas || []), ...(data.reservas.pasadas || [])];
                                    todasReservas.forEach(reserva => {
                                        eventos.push({
                                            title: `Mi reserva: ${reserva.actividad?.Nombre_Actividad || 'Actividad'}`,
                                            start: reserva.Fecha_Reserva,
                                            backgroundColor: '#8B5CF6',
                                            borderColor: '#8B5CF6',
                                            textColor: '#FFFFFF',
                                            tipo: 'reserva',
                                            extendedProps: {
                                                reserva: reserva
                                            }
                                        });
                                    });
                                }
                                successCallback(eventos);
                            })
                            .catch(error => {
                                console.error('Error cargando reservas:', error);
                                successCallback(eventos);
                            });
                    } else {
                        successCallback(eventos);
                    }
                })
                .catch(error => {
                    console.error('Error cargando actividades:', error);
                    failureCallback(error);
                });
        }

        function mostrarDetallesEvento(evento) {
            const tipo = evento.extendedProps?.tipo || evento.tipo;

            if (tipo === 'actividad' && evento.extendedProps?.actividad) {
                const actividad = evento.extendedProps.actividad;
                openReservaModal(actividad.id, actividad.Nombre_Actividad);
            } else if (tipo === 'reserva' && evento.extendedProps?.reserva) {
                const reserva = evento.extendedProps.reserva;
                verDetallesReserva(reserva.id);
            } else if (tipo === 'festivo') {
                // Mostrar información del día festivo
                alert(`${evento.title}\n\nDía festivo en Colombia`);
            }
        }

        // ==================== INICIALIZACIÓN ====================
        document.addEventListener('DOMContentLoaded', function() {
            // ... código existente ...

            // Inicializar mapa y calendario después de cargar categorías
            setTimeout(() => {
                inicializarMapa();
                inicializarCalendario();
            }, 1500);
        });
    </script>
</body>
</html>