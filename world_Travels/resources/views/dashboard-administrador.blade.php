@extends('components.dashboard-layout')

@section('title', 'Administrador - WORLD TRAVELS')

@section('nav-links')
<a href="{{ route('dashboard-administrador') }}" class="text-gray-700 hover:text-blue-600 transition">Panel</a>
<a href="{{ route('perfil-administrador') }}" class="text-gray-700 hover:text-blue-600 transition">Mi Perfil</a>
@endsection

@section('hero')
<section class="relative bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700 text-white py-20 overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-20"></div>
    <div class="absolute inset-0" style="background-image: url('https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80'); background-size: cover; background-position: center;"></div>
    <div class="relative container mx-auto px-4 text-center">
        <h1 class="text-5xl font-bold mb-4">Panel Administrativo</h1>
        <p class="text-xl mb-8 max-w-2xl mx-auto">Bienvenido, Administrador <span id="user-name" class="font-semibold"></span>. Gestiona el sistema WORLD TRAVELS con facilidad.</p>
        <div class="flex justify-center space-x-4">
            <button onclick="manageUsers()" class="bg-white text-blue-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition duration-300 transform hover:scale-105">
                Gestionar Usuarios
            </button>
            <button onclick="viewReports()" class="border-2 border-white text-white px-8 py-3 rounded-full font-semibold hover:bg-white hover:text-blue-600 transition duration-300">
                Ver Reportes
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
@include('components.stats-section')
@include('components.admin-quick-actions')

<!-- Secciones Principales -->
<div class="space-y-16">
    <!-- Gestión del Sistema -->
    <section class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-10 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-4xl font-bold text-white">Gestión del Sistema</h2>
                    <p class="text-blue-100 mt-2 text-lg">Administra todos los aspectos de WORLD TRAVELS</p>
                </div>
            </div>
        </div>

        <div class="p-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <button onclick="manageUsers()" class="bg-gradient-to-br from-blue-50 to-blue-100 p-8 rounded-2xl hover:shadow-xl transition duration-300 border border-blue-200 group">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mr-4 group-hover:scale-110 transition duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-blue-800">Usuarios</h3>
                    </div>
                    <p class="text-blue-600 text-left">Gestiona cuentas de usuarios, permisos y roles del sistema</p>
                </button>

                <button onclick="manageActivities()" class="bg-gradient-to-br from-green-50 to-green-100 p-8 rounded-2xl hover:shadow-xl transition duration-300 border border-green-200 group">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center mr-4 group-hover:scale-110 transition duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-green-800">Actividades</h3>
                    </div>
                    <p class="text-green-600 text-left">Crea, edita y administra todas las actividades turísticas</p>
                </button>

                <button onclick="manageCompanies()" class="bg-gradient-to-br from-purple-50 to-purple-100 p-8 rounded-2xl hover:shadow-xl transition duration-300 border border-purple-200 group">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center mr-4 group-hover:scale-110 transition duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-purple-800">Empresas</h3>
                    </div>
                    <p class="text-purple-600 text-left">Administra empresas, empleados y sus actividades</p>
                </button>

                <button onclick="window.manageCategories && window.manageCategories()" class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-8 rounded-2xl hover:shadow-xl transition duration-300 border border-yellow-200 group">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-yellow-600 rounded-full flex items-center justify-center mr-4 group-hover:scale-110 transition duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-yellow-800">Categorías</h3>
                    </div>
                    <p class="text-yellow-600 text-left">Organiza actividades por categorías temáticas</p>
                </button>

                <button onclick="window.manageMunicipios && window.manageMunicipios()" class="bg-gradient-to-br from-red-50 to-red-100 p-8 rounded-2xl hover:shadow-xl transition duration-300 border border-red-200 group">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center mr-4 group-hover:scale-110 transition duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-red-800">Municipios</h3>
                    </div>
                    <p class="text-red-600 text-left">Gestiona la información geográfica de municipios</p>
                </button>

                <button onclick="window.managePublications && window.managePublications()" class="bg-gradient-to-br from-pink-50 to-pink-100 p-8 rounded-2xl hover:shadow-xl transition duration-300 border border-pink-200 group">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-pink-600 rounded-full flex items-center justify-center mr-4 group-hover:scale-110 transition duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-pink-800">Publicaciones</h3>
                    </div>
                    <p class="text-pink-600 text-left">Modera contenido y publicaciones de usuarios</p>
                </button>
            </div>
        </div>
    </section>

    <!-- Gestión de Contenido -->
    <section class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden" id="content-section">
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-10 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-4xl font-bold text-white">Gestión de Contenido</h2>
                    <p class="text-indigo-100 mt-2 text-lg">Administra el contenido dinámico del sistema</p>
                </div>
            </div>
        </div>

        <div class="p-10" id="list-section">
            <!-- Lista se cargará aquí -->
        </div>
    </section>
</div>
@endsection

@section('modals')
<!-- Modal para gestionar categorías -->
<div id="categoriesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Gestión de Categorías</h3>
                <button onclick="closeCategoriesModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Botón crear categoría -->
            <div class="mb-4">
                <button onclick="showCreateCategoryForm()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Crear Nueva Categoría
                </button>
            </div>

            <!-- Tabla de categorías activas -->
            <div class="mb-8">
                <h4 class="text-lg font-semibold mb-4 text-green-600">Categorías Activas</h4>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-green-50">
                                <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Nombre</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Descripción</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Estado</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="active-categories-table">
                            <!-- Categorías activas se cargarán aquí -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabla de categorías inactivas -->
            <div>
                <h4 class="text-lg font-semibold mb-4 text-red-600">Categorías Inactivas</h4>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-red-50">
                                <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Nombre</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Descripción</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Estado</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="inactive-categories-table">
                            <!-- Categorías inactivas se cargarán aquí -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear/editar categoría -->
<div id="categoryFormModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="categoryFormTitle">Crear Categoría</h3>
            <form id="categoryForm">
                <input type="hidden" id="categoryId" name="categoryId">
                <div class="space-y-4">
                    <div>
                        <label for="categoryNombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" id="categoryNombre" name="nombre" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="categoryDescripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea id="categoryDescripcion" name="descripcion" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <div>
                        <label for="categoryImagen" class="block text-sm font-medium text-gray-700">Imagen (URL)</label>
                        <input type="url" id="categoryImagen" name="imagen" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" id="categoryEstado" name="estado" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Activo</span>
                        </label>
                    </div>
                </div>
                <div class="flex justify-end mt-6 space-x-3">
                    <button type="button" onclick="closeCategoryFormModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-md hover:bg-gray-300">Cancelar</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para crear/editar usuario -->
<div id="userFormModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="userFormTitle">Crear Usuario</h3>
            <form id="userForm" onsubmit="event.preventDefault(); saveUser();">
                <input type="hidden" id="userId" name="userId">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="userNombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" id="userNombre" name="nombre" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="userApellido" class="block text-sm font-medium text-gray-700">Apellido</label>
                            <input type="text" id="userApellido" name="apellido" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    <div>
                        <label for="userEmail" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="userEmail" name="email" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="userTelefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input type="text" id="userTelefono" name="telefono" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="userNacionalidad" class="block text-sm font-medium text-gray-700">Nacionalidad</label>
                            <input type="text" id="userNacionalidad" name="nacionalidad" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    <div>
                        <label for="userRol" class="block text-sm font-medium text-gray-700">Rol</label>
                        <select id="userRol" name="rol" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="Turista">Turista</option>
                            <option value="Guía Turístico">Guía Turístico</option>
                            <option value="Administrador">Administrador</option>
                        </select>
                    </div>
                    <div>
                        <label for="userContraseña" class="block text-sm font-medium text-gray-700">Contraseña</label>
                        <input type="password" id="userContraseña" name="contraseña" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-sm text-gray-500 mt-1">Dejar vacío para mantener la contraseña actual (solo para edición)</p>
                    </div>
                </div>
                <div class="flex justify-end mt-6 space-x-3">
                    <button type="button" onclick="closeUserFormModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-md hover:bg-gray-300">Cancelar</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para crear/editar actividad -->
<div id="activityFormModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="activityFormTitle">Crear Actividad</h3>
            <form id="activityForm" onsubmit="event.preventDefault(); saveActivity();">
                <input type="hidden" id="activityId" name="activityId">
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="activityNombre" class="block text-sm font-medium text-gray-700">Nombre de la Actividad</label>
                            <input type="text" id="activityNombre" name="nombre" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div class="md:col-span-2">
                            <label for="activityDescripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea id="activityDescripcion" name="descripcion" rows="3" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"></textarea>
                        </div>
                        <div>
                            <label for="activityFecha" class="block text-sm font-medium text-gray-700">Fecha</label>
                            <input type="date" id="activityFecha" name="fecha" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label for="activityHora" class="block text-sm font-medium text-gray-700">Hora</label>
                            <input type="time" id="activityHora" name="hora" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label for="activityPrecio" class="block text-sm font-medium text-gray-700">Precio</label>
                            <input type="number" id="activityPrecio" name="precio" step="0.01" min="0" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label for="activityCupo" class="block text-sm font-medium text-gray-700">Cupo Máximo</label>
                            <input type="number" id="activityCupo" name="cupo" min="1" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label for="activityCategoria" class="block text-sm font-medium text-gray-700">Categoría</label>
                            <select id="activityCategoria" name="categoria" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                                <option value="">Cargando categorías...</option>
                            </select>
                        </div>
                        <div>
                            <label for="activityMunicipio" class="block text-sm font-medium text-gray-700">Municipio</label>
                            <select id="activityMunicipio" name="municipio" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                                <option value="">Cargando municipios...</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label for="activityUbicacion" class="block text-sm font-medium text-gray-700">Ubicación</label>
                            <input type="text" id="activityUbicacion" name="ubicacion" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div class="md:col-span-2">
                            <label for="activityImagen" class="block text-sm font-medium text-gray-700">Imagen (URL)</label>
                            <input type="url" id="activityImagen" name="imagen" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-6 space-x-3">
                    <button type="button" onclick="closeActivityFormModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-md hover:bg-gray-300">Cancelar</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para gestionar empresas -->
<div id="companiesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Gestión de Empresas</h3>
                <button onclick="closeCompaniesModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Botón crear empresa -->
            <div class="mb-4">
                <button onclick="showCreateCompanyForm()" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                    Crear Nueva Empresa
                </button>
            </div>

            <!-- Tabla de empresas activas -->
            <div class="mb-8">
                <h4 class="text-lg font-semibold mb-4 text-green-600">Empresas Activas</h4>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-green-50">
                                <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Nombre</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Email</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Ciudad</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Estado</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="active-companies-table">
                            <!-- Empresas activas se cargarán aquí -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabla de empresas bloqueadas -->
            <div>
                <h4 class="text-lg font-semibold mb-4 text-red-600">Empresas Bloqueadas</h4>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-red-50">
                                <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Nombre</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Email</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Ciudad</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Estado</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="inactive-companies-table">
                            <!-- Empresas bloqueadas se cargarán aquí -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear/editar empresa -->
<div id="companyFormModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="companyFormTitle">Crear Empresa</h3>
            <form id="companyForm" onsubmit="event.preventDefault(); saveCompany();">
                <input type="hidden" id="companyId" name="companyId">
                <div class="space-y-6">
                    <div class="space-y-4">
                        <div>
                            <label for="companyNumero" class="block text-sm font-medium text-gray-700">Número NIT</label>
                            <input type="text" id="companyNumero" name="numero" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label for="companyNombre" class="block text-sm font-medium text-gray-700">Nombre de la Empresa</label>
                            <input type="text" id="companyNombre" name="nombre" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label for="companyDireccion" class="block text-sm font-medium text-gray-700">Dirección</label>
                            <input type="text" id="companyDireccion" name="direccion" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label for="companyCiudad" class="block text-sm font-medium text-gray-700">Ciudad</label>
                            <input type="text" id="companyCiudad" name="ciudad" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label for="companyCorreo" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                            <input type="email" id="companyCorreo" name="correo" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label for="companyContraseña" class="block text-sm font-medium text-gray-700">Contraseña</label>
                            <input type="password" id="companyContraseña" name="contraseña" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="companyEstado" name="estado" checked class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                            <label for="companyEstado" class="ml-2 text-sm text-gray-700">Empresa activa</label>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-6 space-x-3">
                    <button type="button" onclick="closeCompanyFormModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-md hover:bg-gray-300">Cancelar</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 border border-transparent rounded-md hover:bg-purple-700">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para ver balance de empresa -->
<div id="companyReportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
        <div id="companyReportContent">
            <!-- El contenido del reporte se cargará aquí -->
        </div>
    </div>
</div>

<!-- Modal para gestionar municipios -->
<div id="municipiosModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Gestión de Municipios</h3>
                <button onclick="closeMunicipiosModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Botón crear municipio -->
            <div class="mb-4">
                <button onclick="showCreateMunicipioForm()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Crear Nuevo Municipio
                </button>
            </div>

            <!-- Tabla de municipios -->
            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Nombre</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Departamento</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="municipios-table">
                        <!-- Municipios se cargarán aquí -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear/editar municipio -->
<div id="municipioFormModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="municipioFormTitle">Crear Municipio</h3>
            <form id="municipioForm" onsubmit="event.preventDefault(); saveMunicipio();">
                <input type="hidden" id="municipioId" name="municipioId">
                <div class="space-y-6">
                    <div class="space-y-4">
                        <div>
                            <label for="municipioNombre" class="block text-sm font-medium text-gray-700">Nombre del Municipio</label>
                            <input type="text" id="municipioNombre" name="nombre" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                        </div>
                        <div>
                            <label for="municipioDepartamento" class="block text-sm font-medium text-gray-700">Departamento</label>
                            <select id="municipioDepartamento" name="departamento" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                <option value="">Cargando departamentos...</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-6 space-x-3">
                    <button type="button" onclick="closeMunicipioFormModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-md hover:bg-gray-300">Cancelar</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para ver mapa de municipios -->
<div id="municipiosMapModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Mapa de Municipios con Actividades Activas</h3>
                <button onclick="closeMunicipiosMapModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="municipios-map" style="height: 600px; width: 100%; border-radius: 8px;"></div>
        </div>
    </div>
</div>

<!-- Modales adicionales para gestión completa -->
<!-- Ver actividad, municipio, empresa, etc. -->
@endsection

@vite('resources/js/dashboard-admin.js')