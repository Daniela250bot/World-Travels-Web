<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador - WORLD TRAVELS</title>
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
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 transition">Administrador</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="text-gray-700 hover:text-blue-600 transition bg-transparent border-none cursor-pointer">Cerrar Sesión</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-12">
        <div class="mb-8">
            <h2 class="text-4xl font-bold text-center mb-4 text-gray-800">Panel Administrativo</h2>
            <p class="text-center text-gray-600">Bienvenido, Administrador <span id="user-name" class="font-semibold"></span></p>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12" id="stats-section">
            <!-- Estadísticas se cargarán aquí -->
        </div>

        <!-- Panel de acciones administrativas -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-12">
            <h3 class="text-2xl font-bold mb-6 text-gray-800">Panel Administrativo</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <button onclick="manageUsers()" class="bg-blue-600 text-white p-6 rounded-lg hover:bg-blue-700 transition text-center">
                    <h4 class="text-xl font-semibold mb-2">Gestionar Usuarios</h4>
                    <p>Administra cuentas de usuario</p>
                </button>
                <button onclick="manageActivities()" class="bg-green-600 text-white p-6 rounded-lg hover:bg-green-700 transition text-center">
                    <h4 class="text-xl font-semibold mb-2">Gestionar Actividades</h4>
                    <p>Crear, editar y eliminar actividades</p>
                </button>
                <button onclick="manageCategories()" class="bg-yellow-600 text-white p-6 rounded-lg hover:bg-yellow-700 transition text-center">
                    <h4 class="text-xl font-semibold mb-2">Categorías</h4>
                    <p>Gestiona categorías de actividades</p>
                </button>
                <button onclick="viewReports()" class="bg-purple-600 text-white p-6 rounded-lg hover:bg-purple-700 transition text-center">
                    <h4 class="text-xl font-semibold mb-2">Reportes</h4>
                    <p>Visualiza estadísticas del sistema</p>
                </button>
                <button onclick="manageMunicipios()" class="bg-red-600 text-white p-6 rounded-lg hover:bg-red-700 transition text-center">
                    <h4 class="text-xl font-semibold mb-2">Municipios</h4>
                    <p>Gestiona municipios</p>
                </button>
                <button onclick="manageCompanies()" class="bg-gray-600 text-white p-6 rounded-lg hover:bg-gray-700 transition text-center">
                    <h4 class="text-xl font-semibold mb-2">Empresas</h4>
                    <p>Gestiona empresas y empleados</p>
                </button>
                
            </div>
        </div>

        <!-- Lista de gestión de usuarios -->
        <div class="bg-white rounded-xl shadow-lg p-8" id="list-section">
            <!-- Lista se cargará aquí -->
        </div>

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
                                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" id="categoryNombre" name="nombre" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Descripción</label>
                                <textarea id="categoryDescripcion" name="descripcion" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Imagen (URL)</label>
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

        <!-- Modal para crear actividad -->
        <div id="createActivityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Crear Nueva Actividad</h3>
                    <form id="createActivityForm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nombre de la Actividad</label>
                                <input type="text" id="nombre_actividad" name="Nombre_Actividad" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Categoría</label>
                                <select id="idCategoria" name="idCategoria" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Seleccionar categoría</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Municipio</label>
                                <select id="idMunicipio" name="idMunicipio" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Seleccionar municipio</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fecha</label>
                                <input type="date" id="fecha_actividad" name="Fecha_Actividad" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Hora</label>
                                <input type="time" id="hora_actividad" name="Hora_Actividad" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Precio</label>
                                <input type="number" id="precio" name="Precio" step="0.01" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cupo Máximo</label>
                                <input type="number" id="cupo_maximo" name="Cupo_Maximo" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Ubicación</label>
                                <input type="text" id="ubicacion" name="Ubicacion" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Imagen (URL)</label>
                                <input type="url" id="imagen" name="Imagen" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <button type="button" onclick="closeCreateActivityModal()" class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-md hover:bg-gray-300">Cancelar</button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">Crear Actividad</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal para editar actividad -->
        <div id="editActivityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Editar Actividad</h3>
                    <form id="editActivityForm">
                        <input type="hidden" id="editActivityId">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nombre de la Actividad</label>
                                <input type="text" id="editNombre_Actividad" name="Nombre_Actividad" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Categoría (No editable)</label>
                                <select id="editIdCategoria" disabled class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100">
                                    <option value="">Seleccionar categoría</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Municipio (No editable)</label>
                                <select id="editIdMunicipio" disabled class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100">
                                    <option value="">Seleccionar municipio</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fecha</label>
                                <input type="date" id="editFecha_Actividad" name="Fecha_Actividad" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Hora</label>
                                <input type="time" id="editHora_Actividad" name="Hora_Actividad" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Precio</label>
                                <input type="number" id="editPrecio" name="Precio" step="0.01" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cupo Máximo</label>
                                <input type="number" id="editCupo_Maximo" name="Cupo_Maximo" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Ubicación</label>
                                <input type="text" id="editUbicacion" name="Ubicacion" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Descripción</label>
                                <textarea id="editDescripcion" name="Descripcion" rows="3" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Imagen (URL)</label>
                                <input type="url" id="editImagen" name="Imagen" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <button type="button" onclick="closeEditActivityModal()" class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-md hover:bg-gray-300">Cancelar</button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">Actualizar Actividad</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal para ver detalles de actividad -->
        <div id="viewActivityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Detalles de la Actividad</h3>
                    <div id="activityDetails" class="space-y-4">
                        <!-- Los detalles se cargarán aquí -->
                    </div>
                    <div class="flex justify-end mt-4">
                        <button type="button" onclick="closeViewActivityModal()" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para crear usuario -->
        <div id="createUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Crear Nuevo Usuario</h3>
                    <form id="createUserForm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" id="Nombre" name="Nombre" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Apellido</label>
                                <input type="text" id="Apellido" name="Apellido" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="Email" name="Email" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Contraseña</label>
                                <input type="password" id="Contraseña" name="Contraseña" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                                <input type="text" id="Telefono" name="Telefono" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nacionalidad</label>
                                <input type="text" id="Nacionalidad" name="Nacionalidad" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Rol</label>
                                <select id="Rol" name="Rol" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Seleccionar rol</option>
                                    <option value="Turista">Turista</option>
                                    <option value="Guía Turístico">Guía Turístico</option>
                                    <option value="Administrador">Administrador</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <button type="button" onclick="closeCreateUserModal()" class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-md hover:bg-gray-300">Cancelar</button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">Crear Usuario</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal para editar usuario -->
        <div id="editUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Editar Usuario</h3>
                    <form id="editUserForm">
                        <input type="hidden" id="editUserId" name="id">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" id="editNombre" name="Nombre" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Apellido</label>
                                <input type="text" id="editApellido" name="Apellido" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="editEmail" name="Email" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Contraseña (dejar vacío para no cambiar)</label>
                                <input type="password" id="editContraseña" name="Contraseña" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                                <input type="text" id="editTelefono" name="Telefono" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nacionalidad</label>
                                <input type="text" id="editNacionalidad" name="Nacionalidad" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Rol</label>
                                <select id="editRol" name="Rol" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Seleccionar rol</option>
                                    <option value="Turista">Turista</option>
                                    <option value="Guía Turístico">Guía Turístico</option>
                                    <option value="Administrador">Administrador</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <button type="button" onclick="closeEditUserModal()" class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-md hover:bg-gray-300">Cancelar</button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">Actualizar Usuario</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal para crear/editar municipio -->
        <div id="municipioModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4" id="municipioModalTitle">Crear Nuevo Municipio</h3>
                    <form id="municipioForm">
                        <input type="hidden" id="municipioId" name="id">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Nombre del Municipio</label>
                                <input type="text" id="Nombre_Municipio" name="Nombre_Municipio" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Departamento</label>
                                <select id="idDepartamento" name="idDepartamento" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Seleccionar departamento</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <button type="button" onclick="closeMunicipioModal()" class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-md hover:bg-gray-300">Cancelar</button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal para crear/editar empresa -->
        <div id="empresaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4" id="empresaModalTitle">Crear Nueva Empresa</h3>
                    <form id="empresaForm">
                        <input type="hidden" id="empresaId" name="id">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Número</label>
                                <input type="text" id="numero" name="numero" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" id="nombre" name="nombre" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Descripción</label>
                                <textarea id="descripcion" name="descripcion" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Dirección</label>
                                <input type="text" id="direccion" name="direccion" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ciudad</label>
                                <input type="text" id="ciudad" name="ciudad" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Correo</label>
                                <input type="email" id="correo" name="correo" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Contraseña (dejar vacío para no cambiar)</label>
                                <input type="password" id="contraseña" name="contraseña" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Código de Verificación</label>
                                <input type="text" id="codigo_verificacion" name="codigo_verificacion" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                                <input type="text" id="telefono" name="telefono" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sitio Web</label>
                                <input type="url" id="sitio_web" name="sitio_web" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Políticas</label>
                                <textarea id="politicas" name="politicas" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                            </div>
                        </div>
                        <div class="flex justify-end mt-6 space-x-3">
                            <button type="button" onclick="closeEmpresaModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-md hover:bg-gray-300">Cancelar</button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal para ver reporte detallado de empresa -->
        <div id="empresaReporteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Reporte Detallado de Empresa</h3>
                        <button onclick="closeEmpresaReporteModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div id="empresaReporteContent" class="space-y-6">
                        <!-- El contenido del reporte se cargará aquí -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para gestión de actividades de empresa -->
        <div id="empresaActividadesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-4/5 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Gestión de Actividades de Empresa</h3>
                        <button onclick="closeEmpresaActividadesModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="mb-4">
                        <button onclick="showCreateCompanyActivityForm()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Crear Nueva Actividad
                        </button>
                    </div>
                    <div id="empresaActividadesContent">
                        <!-- Las actividades se cargarán aquí -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para gestión de reservas de empresa -->
        <div id="empresaReservasModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-4/5 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Gestión de Reservas de Empresa</h3>
                        <button onclick="closeEmpresaReservasModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div id="empresaReservasContent">
                        <!-- Las reservas se cargarán aquí -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para crear/editar actividad de empresa -->
        <div id="empresaActividadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4" id="empresaActividadModalTitle">Crear Actividad</h3>
                    <form id="empresaActividadForm">
                        <input type="hidden" id="empresaActividadId">
                        <input type="hidden" id="empresaIdActividad">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Nombre de la Actividad</label>
                                <input type="text" id="empresaNombreActividad" name="Nombre_Actividad" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Categoría</label>
                                <select id="empresaIdCategoria" name="idCategoria" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Seleccionar categoría</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Municipio</label>
                                <select id="empresaIdMunicipio" name="idMunicipio" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Seleccionar municipio</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fecha</label>
                                <input type="date" id="empresaFechaActividad" name="Fecha_Actividad" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Hora</label>
                                <input type="time" id="empresaHoraActividad" name="Hora_Actividad" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Precio</label>
                                <input type="number" id="empresaPrecio" name="Precio" step="0.01" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cupo Máximo</label>
                                <input type="number" id="empresaCupoMaximo" name="Cupo_Maximo" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Ubicación</label>
                                <input type="text" id="empresaUbicacion" name="Ubicacion" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Descripción</label>
                                <textarea id="empresaDescripcion" name="Descripcion" rows="3" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Imagen (URL)</label>
                                <input type="url" id="empresaImagen" name="Imagen" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>
                        <div class="flex justify-end mt-6 space-x-3">
                            <button type="button" onclick="closeEmpresaActividadModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-md hover:bg-gray-300">Cancelar</button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Guardar el token JWT en localStorage después del login
        @if(session('jwt_token'))
            localStorage.setItem('token', '{{ session("jwt_token") }}');
            localStorage.setItem('user_role', 'administrador');
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

        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('token');
            if (!token) {
                window.location.href = '{{ route("login") }}';
                return;
            }

            loadUserData();
            loadStats();
            loadUserList();
        });

        function loadUserData() {
            fetch('http://127.0.0.1:8000/api/me', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('user-name').textContent = data.usuario.Nombre;
                }
            })
            .catch(error => {
                console.error('Error cargando datos del usuario:', error);
                localStorage.removeItem('token');
                window.location.href = '{{ route("login") }}';
            });
        }

        function loadStats() {
            const statsSection = document.getElementById('stats-section');

            Promise.all([
                fetch('http://127.0.0.1:8000/api/listarUsuarios').then(r => r.json()),
                fetch('http://127.0.0.1:8000/api/listarActividades').then(r => r.json()),
                fetch('http://127.0.0.1:8000/api/listarReservas').then(r => r.json()),
                fetch('http://127.0.0.1:8000/api/listarEmpresas').then(r => r.json())
            ])
            .then(([usuarios, actividades, reservas, empresas]) => {
                statsSection.innerHTML = `
                    <div class="bg-blue-50 p-6 rounded-lg text-center">
                        <h3 class="text-2xl font-bold text-blue-600">${usuarios.length}</h3>
                        <p class="text-gray-600">Total Usuarios</p>
                    </div>
                    <div class="bg-green-50 p-6 rounded-lg text-center">
                        <h3 class="text-2xl font-bold text-green-600">${actividades.length}</h3>
                        <p class="text-gray-600">Total Actividades</p>
                    </div>
                    <div class="bg-yellow-50 p-6 rounded-lg text-center">
                        <h3 class="text-2xl font-bold text-yellow-600">${reservas.length}</h3>
                        <p class="text-gray-600">Total Reservas</p>
                    </div>
                    <div class="bg-purple-50 p-6 rounded-lg text-center">
                        <h3 class="text-2xl font-bold text-purple-600">${empresas.data.length}</h3>
                        <p class="text-gray-600">Total Empresas</p>
                    </div>
                `;
            })
            .catch(error => console.error('Error cargando estadísticas:', error));
        }

        function loadUserList() {
            const listSection = document.getElementById('list-section');

            fetch('http://127.0.0.1:8000/api/listarUsuarios', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(data => {
                let html = '<h3 class="text-2xl font-bold mb-6 text-gray-800">Gestión de Usuarios</h3>';
                html += '<button onclick="showCreateUserForm()" class="bg-blue-600 text-white px-4 py-2 rounded mb-4">Crear Nuevo Usuario</button>';
                html += '<div class="overflow-x-auto">';
                html += '<table class="w-full table-auto">';
                html += '<thead><tr class="bg-gray-100"><th class="px-4 py-2">Nombre</th><th class="px-4 py-2">Email</th><th class="px-4 py-2">Rol</th><th class="px-4 py-2">Acciones</th></tr></thead>';
                html += '<tbody>';
                data.forEach(usuario => {
                    html += `
                        <tr class="border-b">
                            <td class="px-4 py-2">${usuario.Nombre} ${usuario.Apellido}</td>
                            <td class="px-4 py-2">${usuario.Email}</td>
                            <td class="px-4 py-2">${usuario.Rol}</td>
                            <td class="px-4 py-2">
                                <button onclick="editUser(${usuario.id})" class="bg-blue-500 text-white px-2 py-1 rounded text-sm mr-2">Editar</button>
                                <button onclick="deleteUser(${usuario.id})" class="bg-red-500 text-white px-2 py-1 rounded text-sm">Eliminar</button>
                            </td>
                        </tr>
                    `;
                });
                html += '</tbody></table></div>';
                listSection.innerHTML = html;
            })
            .catch(error => console.error('Error cargando lista de usuarios:', error));
        }

        function loadCompanyList() {
            const listSection = document.getElementById('list-section');

            fetch('http://127.0.0.1:8000/api/listarEmpresas', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(data => {
                let html = '<h3 class="text-2xl font-bold mb-6 text-gray-800">Gestión de Empresas</h3>';
                html += '<button onclick="showCreateCompanyForm()" class="bg-blue-600 text-white px-4 py-2 rounded mb-4">Crear Nueva Empresa</button>';
                html += '<div class="overflow-x-auto">';
                html += '<table class="w-full table-auto">';
                html += '<thead><tr class="bg-gray-100"><th class="px-4 py-2">Nombre</th><th class="px-4 py-2">Correo</th><th class="px-4 py-2">Ciudad</th><th class="px-4 py-2">Acciones</th></tr></thead>';
                html += '<tbody>';
                data.data.forEach(empresa => {
                    html += `
                        <tr class="border-b">
                            <td class="px-4 py-2">${empresa.nombre}</td>
                            <td class="px-4 py-2">${empresa.correo}</td>
                            <td class="px-4 py-2">${empresa.ciudad}</td>
                            <td class="px-4 py-2">
                                <button onclick="editCompany(${empresa.id})" class="bg-blue-500 text-white px-2 py-1 rounded text-sm mr-2">Editar</button>
                                <button onclick="viewCompanyReport(${empresa.id})" class="bg-green-500 text-white px-2 py-1 rounded text-sm mr-2">Reporte</button>
                                <button onclick="manageCompanyActivities(${empresa.id})" class="bg-purple-500 text-white px-2 py-1 rounded text-sm mr-2">Actividades</button>
                                <button onclick="manageCompanyReservations(${empresa.id})" class="bg-indigo-500 text-white px-2 py-1 rounded text-sm mr-2">Reservas</button>
                                <button onclick="manageEmployees(${empresa.id})" class="bg-yellow-500 text-white px-2 py-1 rounded text-sm mr-2">Empleados</button>
                                <button onclick="deleteCompany(${empresa.id})" class="bg-red-500 text-white px-2 py-1 rounded text-sm">Eliminar</button>
                            </td>
                        </tr>
                    `;
                });
                html += '</tbody></table></div>';
                listSection.innerHTML = html;
            })
            .catch(error => console.error('Error cargando lista de empresas:', error));
        }

        function loadActivityList() {
            const listSection = document.getElementById('list-section');

            fetch('http://127.0.0.1:8000/api/listarActividades', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(data => {
                let html = '<h3 class="text-2xl font-bold mb-6 text-gray-800">Gestión de Actividades</h3>';
                html += '<button onclick="showCreateActivityForm()" class="bg-blue-600 text-white px-4 py-2 rounded mb-4">Crear Nueva Actividad</button>';
                html += '<div class="overflow-x-auto">';
                html += '<table class="w-full table-auto">';
                html += '<thead><tr class="bg-gray-100"><th class="px-4 py-2">Nombre</th><th class="px-4 py-2">Fecha</th><th class="px-4 py-2">Precio</th><th class="px-4 py-2">Ubicación</th><th class="px-4 py-2">Acciones</th></tr></thead>';
                html += '<tbody>';
                data.forEach(actividad => {
                    html += `
                        <tr class="border-b">
                            <td class="px-4 py-2">${actividad.Nombre_Actividad}</td>
                            <td class="px-4 py-2">${actividad.Fecha_Actividad}</td>
                            <td class="px-4 py-2">$${actividad.Precio}</td>
                            <td class="px-4 py-2">${actividad.Ubicacion}</td>
                            <td class="px-4 py-2">
                                <button onclick="editActivity(${actividad.id})" class="bg-blue-500 text-white px-2 py-1 rounded text-sm mr-2">Editar</button>
                                <button onclick="viewActivity(${actividad.id})" class="bg-green-500 text-white px-2 py-1 rounded text-sm mr-2">Ver</button>
                                <button onclick="deleteActivity(${actividad.id})" class="bg-red-500 text-white px-2 py-1 rounded text-sm">Eliminar</button>
                            </td>
                        </tr>
                    `;
                });
                html += '</tbody></table></div>';
                listSection.innerHTML = html;
            })
            .catch(error => console.error('Error cargando lista de actividades:', error));
        }

        // Event listener para crear actividad
        document.getElementById('createActivityForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            // Convertir null a cadena vacía para campos opcionales
            if (data.Imagen === null || data.Imagen === 'null') {
                data.Imagen = '';
            }

            fetchWithAuth('http://127.0.0.1:8000/api/crearActividades', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Actividad creada exitosamente');
                    closeCreateActivityModal();
                    loadActivityList(); // Recargar lista
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
        });

        // Event listener para crear/editar municipio
        document.getElementById('municipioForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            const municipioId = data.id;
            const method = municipioId ? 'PUT' : 'POST';
            const url = municipioId
                ? `http://127.0.0.1:8000/api/actualizarMunicipios/${municipioId}`
                : 'http://127.0.0.1:8000/api/crearMunicipios';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Municipio guardado exitosamente');
                    closeMunicipioModal();
                    loadMunicipioList();
                } else {
                    alert('Error al guardar municipio: ' + JSON.stringify(result.errors || result.message));
                }
            })
            .catch(error => {
                console.error('Error guardando municipio:', error);
                alert('Error al guardar municipio');
            });
        });

        // Event listener para crear usuario
        document.getElementById('createUserForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            fetch('http://127.0.0.1:8000/api/crearUsuarios', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.id) {
                    alert('Usuario creado exitosamente');
                    closeCreateUserModal();
                    loadUserList();
                } else {
                    alert('Error al crear usuario: ' + JSON.stringify(result));
                }
            })
            .catch(error => {
                console.error('Error creando usuario:', error);
                alert('Error al crear usuario');
            });
        });

        // Event listener para editar usuario
        document.getElementById('editUserForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            const userId = data.id;

            fetch(`http://127.0.0.1:8000/api/usuarios/${userId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.id) {
                    alert('Usuario actualizado exitosamente');
                    closeEditUserModal();
                    loadUserList();
                } else {
                    alert('Error al actualizar usuario: ' + JSON.stringify(result.errors || result.message));
                }
            })
            .catch(error => {
                console.error('Error actualizando usuario:', error);
                alert('Error al actualizar usuario');
            });
        });

        // Event listener para editar actividad
        document.getElementById('editActivityForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            const activityId = document.getElementById('editActivityId').value;

            fetch(`http://127.0.0.1:8000/api/actualizarActividades/${activityId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.id) {
                    alert('Actividad actualizada exitosamente');
                    closeEditActivityModal();
                    loadActivityList();
                } else {
                    alert('Error al actualizar actividad: ' + JSON.stringify(result.errors || result.message));
                }
            })
            .catch(error => {
                console.error('Error actualizando actividad:', error);
                alert('Error al actualizar actividad');
            });
        });

        // Funciones de acción (placeholders)
        function manageUsers() { loadUserList(); }
        function manageActivities() { loadActivityList(); }
        function manageCategories() { loadCategoriesList(); document.getElementById('categoriesModal').classList.remove('hidden'); }
        function manageMunicipios() { loadMunicipioList(); }
        function manageCompanies() { loadCompanyList(); }
        function viewReports() { window.location.href = '{{ route("reportes") }}'; }
        function editUser(id) {
            fetch(`http://127.0.0.1:8000/api/usuarios/${id}`, {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('editUserId').value = data.id;
                document.getElementById('editNombre').value = data.Nombre;
                document.getElementById('editApellido').value = data.Apellido;
                document.getElementById('editEmail').value = data.Email;
                document.getElementById('editContraseña').value = '';
                document.getElementById('editTelefono').value = data.Telefono;
                document.getElementById('editNacionalidad').value = data.Nacionalidad;
                document.getElementById('editRol').value = data.Rol;
                showEditUserModal();
            })
            .catch(error => {
                console.error('Error cargando usuario:', error);
                alert('Error al cargar datos del usuario');
            });
        }

        function deleteUser(id) {
            if (confirm('¿Estás seguro de que quieres eliminar este usuario?')) {
                fetch(`http://127.0.0.1:8000/api/usuarios/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('token')
                    }
                })
                .then(response => response.json())
                .then(result => {
                    if (result.message) {
                        alert('Usuario eliminado exitosamente');
                        loadUserList();
                    } else {
                        alert('Error al eliminar usuario: ' + JSON.stringify(result));
                    }
                })
                .catch(error => {
                    console.error('Error eliminando usuario:', error);
                    alert('Error al eliminar usuario');
                });
            }
        }
        function editCompany(id) { alert('Editar empresa ' + id); }
        function viewCompanyReport(id) { alert('Ver reporte empresa ' + id); }
        function manageEmployees(id) { alert('Gestionar empleados empresa ' + id); }
        function deleteCompany(id) { alert('Eliminar empresa ' + id); }
        function showCreateCompanyForm() { alert('Mostrar formulario crear empresa'); }
        function editActivity(id) {
            Promise.all([
                fetch(`http://127.0.0.1:8000/api/actividades/${id}`, {
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('token')
                    }
                }).then(response => response.json()),
                loadCategories(),
                loadMunicipios()
            ])
            .then(([data]) => {
                document.getElementById('editActivityId').value = data.id;
                document.getElementById('editNombre_Actividad').value = data.Nombre_Actividad;
                document.getElementById('editIdCategoria').value = data.idCategoria || '';
                document.getElementById('editIdMunicipio').value = data.idMunicipio || '';
                document.getElementById('editFecha_Actividad').value = data.Fecha_Actividad;
                document.getElementById('editHora_Actividad').value = data.Hora_Actividad;
                document.getElementById('editPrecio').value = data.Precio;
                document.getElementById('editCupo_Maximo').value = data.Cupo_Maximo;
                document.getElementById('editUbicacion').value = data.Ubicacion;
                document.getElementById('editDescripcion').value = data.Descripcion;
                document.getElementById('editImagen').value = data.Imagen || '';
                showEditActivityModal();
            })
            .catch(error => {
                console.error('Error cargando actividad:', error);
                alert('Error al cargar datos de la actividad');
            });
        }

        function viewActivity(id) {
            fetch(`http://127.0.0.1:8000/api/actividades/${id}`, {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(data => {
                const detailsDiv = document.getElementById('activityDetails');
                detailsDiv.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-800 mb-2">Información Básica</h4>
                            <p><strong>Nombre:</strong> ${data.Nombre_Actividad}</p>
                            <p><strong>Descripción:</strong> ${data.Descripcion || 'N/A'}</p>
                            <p><strong>Fecha:</strong> ${data.Fecha_Actividad}</p>
                            <p><strong>Hora:</strong> ${data.Hora_Actividad}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-800 mb-2">Detalles Operativos</h4>
                            <p><strong>Precio:</strong> $${data.Precio}</p>
                            <p><strong>Cupo Máximo:</strong> ${data.Cupo_Maximo}</p>
                            <p><strong>Ubicación:</strong> ${data.Ubicacion}</p>
                            <p><strong>Imagen:</strong> ${data.Imagen ? `<a href="${data.Imagen}" target="_blank">Ver Imagen</a>` : 'N/A'}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg md:col-span-2">
                            <h4 class="font-semibold text-gray-800 mb-2">Relaciones</h4>
                            <p><strong>Categoría:</strong> ${data.categoria ? data.categoria.nombre : 'N/A'}</p>
                            <p><strong>Municipio:</strong> ${data.municipio ? data.municipio.Nombre_Municipio : 'N/A'}</p>
                            <p><strong>Usuario:</strong> ${data.usuario ? `${data.usuario.Nombre} ${data.usuario.Apellido}` : 'N/A'}</p>
                        </div>
                    </div>
                `;
                showViewActivityModal();
            })
            .catch(error => {
                console.error('Error cargando actividad:', error);
                alert('Error al cargar la actividad');
            });
        }

        function deleteActivity(id) {
            if (confirm('¿Estás seguro de que quieres eliminar esta actividad?')) {
                fetch(`http://127.0.0.1:8000/api/eliminarActividades/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('token')
                    }
                })
                .then(response => response.json())
                .then(result => {
                    if (result.message) {
                        alert('Actividad eliminada exitosamente');
                        loadActivityList();
                    } else {
                        alert('Error al eliminar actividad: ' + JSON.stringify(result));
                    }
                })
                .catch(error => {
                    console.error('Error eliminando actividad:', error);
                    alert('Error al eliminar actividad');
                });
            }
        }
        function showCreateActivityForm() {
            loadCategories();
            loadMunicipios();
            document.getElementById('createActivityModal').classList.remove('hidden');
        }

        function showCreateUserForm() {
            document.getElementById('createUserModal').classList.remove('hidden');
        }

        function closeCreateActivityModal() {
            document.getElementById('createActivityModal').classList.add('hidden');
            document.getElementById('createActivityForm').reset();
        }

        function showEditActivityModal() {
            document.getElementById('editActivityModal').classList.remove('hidden');
        }

        function closeEditActivityModal() {
            document.getElementById('editActivityModal').classList.add('hidden');
            document.getElementById('editActivityForm').reset();
        }

        function showViewActivityModal() {
            document.getElementById('viewActivityModal').classList.remove('hidden');
        }

        function closeViewActivityModal() {
            document.getElementById('viewActivityModal').classList.add('hidden');
            document.getElementById('activityDetails').innerHTML = '';
        }


        function loadUsers() {
            return fetch('http://127.0.0.1:8000/api/listarUsuarios', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(data => {
                const selects = ['idUsuario'];
                selects.forEach(selectId => {
                    const select = document.getElementById(selectId);
                    if (select) {
                        select.innerHTML = '<option value="">Seleccionar usuario</option>';
                        data.forEach(usuario => {
                            const option = document.createElement('option');
                            option.value = usuario.id;
                            option.textContent = `${usuario.Nombre} ${usuario.Apellido}`;
                            select.appendChild(option);
                        });
                    }
                });
            })
            .catch(error => console.error('Error cargando usuarios:', error));
        }

        function loadMunicipios() {
            return fetch('http://127.0.0.1:8000/api/listarMunicipios', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(data => {
                const selects = ['idMunicipio', 'editIdMunicipio'];
                selects.forEach(selectId => {
                    const select = document.getElementById(selectId);
                    if (select) {
                        select.innerHTML = '<option value="">Seleccionar municipio</option>';
                        data.forEach(municipio => {
                            const option = document.createElement('option');
                            option.value = municipio.id;
                            option.textContent = municipio.Nombre_Municipio;
                            select.appendChild(option);
                        });
                    }
                });
            })
            .catch(error => console.error('Error cargando municipios:', error));
        }

        function showCreateUserForm() {
            document.getElementById('createUserModal').classList.remove('hidden');
        }

        function closeCreateUserModal() {
            document.getElementById('createUserModal').classList.add('hidden');
            document.getElementById('createUserForm').reset();
        }

        function showEditUserModal() {
            document.getElementById('editUserModal').classList.remove('hidden');
        }

        function closeEditUserModal() {
            document.getElementById('editUserModal').classList.add('hidden');
            document.getElementById('editUserForm').reset();
        }

        function loadMunicipioList() {
            const listSection = document.getElementById('list-section');

            fetch('http://127.0.0.1:8000/api/listarMunicipios', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(data => {
                let html = '<h3 class="text-2xl font-bold mb-6 text-gray-800">Gestión de Municipios</h3>';
                html += '<button onclick="showCreateMunicipioForm()" class="bg-blue-600 text-white px-4 py-2 rounded mb-4">Crear Nuevo Municipio</button>';
                html += '<div class="overflow-x-auto">';
                html += '<table class="w-full table-auto">';
                html += '<thead><tr class="bg-gray-100"><th class="px-4 py-2">Nombre</th><th class="px-4 py-2">Departamento</th><th class="px-4 py-2">Acciones</th></tr></thead>';
                html += '<tbody>';
                data.forEach(municipio => {
                    html += `
                        <tr class="border-b">
                            <td class="px-4 py-2">${municipio.Nombre_Municipio}</td>
                            <td class="px-4 py-2">${municipio.departamento ? municipio.departamento.Nombre_Departamento : 'N/A'}</td>
                            <td class="px-4 py-2">
                                <button onclick="editMunicipio(${municipio.id})" class="bg-blue-500 text-white px-2 py-1 rounded text-sm mr-2">Editar</button>
                                <button onclick="deleteMunicipio(${municipio.id})" class="bg-red-500 text-white px-2 py-1 rounded text-sm">Eliminar</button>
                            </td>
                        </tr>
                    `;
                });
                html += '</tbody></table></div>';
                listSection.innerHTML = html;
            })
            .catch(error => console.error('Error cargando lista de municipios:', error));
        }

        function showCreateMunicipioForm() {
            loadDepartamentos();
            document.getElementById('municipioModalTitle').textContent = 'Crear Nuevo Municipio';
            document.getElementById('municipioId').value = '';
            document.getElementById('municipioForm').reset();
            document.getElementById('municipioModal').classList.remove('hidden');
        }

        function closeMunicipioModal() {
            document.getElementById('municipioModal').classList.add('hidden');
            document.getElementById('municipioForm').reset();
        }

        function loadDepartamentos() {
            return fetch('http://127.0.0.1:8000/api/departamentos')
            .then(response => response.json())
            .then(data => {
                const select = document.getElementById('idDepartamento');
                select.innerHTML = '<option value="">Seleccionar departamento</option>';
                data.forEach(dep => {
                    select.innerHTML += `<option value="${dep.id}">${dep.Nombre_Departamento}</option>`;
                });
                return data; // Retornar los datos para la promesa
            })
            .catch(error => {
                console.error('Error cargando departamentos:', error);
                throw error; // Re-throw para que la promesa falle
            });
        }

        function editMunicipio(id) {
            fetch(`http://127.0.0.1:8000/api/municipios/${id}`, {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    const municipio = result.data;
                    loadDepartamentos().then(() => {
                        document.getElementById('municipioModalTitle').textContent = 'Editar Municipio';
                        document.getElementById('municipioId').value = municipio.id;
                        document.getElementById('Nombre_Municipio').value = municipio.Nombre_Municipio;
                        document.getElementById('idDepartamento').value = municipio.idDepartamento;
                        document.getElementById('municipioModal').classList.remove('hidden');
                    });
                }
            })
            .catch(error => console.error('Error cargando municipio:', error));
        }

        function deleteMunicipio(id) {
            if (confirm('¿Estás seguro de que quieres eliminar este municipio?')) {
                fetch(`http://127.0.0.1:8000/api/eliminarMunicipios/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('token')
                    }
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert('Municipio eliminado exitosamente');
                        loadMunicipioList();
                    } else {
                        alert('Error al eliminar municipio: ' + result.message);
                    }
                })
                .catch(error => {
                    console.error('Error eliminando municipio:', error);
                    alert('Error al eliminar municipio');
                });
            }
        }

        function loadCategories() {
            fetch('http://127.0.0.1:8000/api/categories/active')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const select = document.getElementById('idCategoria');
                select.innerHTML = '<option value="">Seleccionar categoría</option>';
                if (Array.isArray(data)) {
                    data.forEach(cat => {
                        select.innerHTML += `<option value="${cat.id}">${cat.nombre}</option>`;
                    });
                }
            })
            .catch(error => {
                console.error('Error cargando categorías activas:', error);
                const select = document.getElementById('idCategoria');
                select.innerHTML = '<option value="">Error al cargar categorías</option>';
            });
        }

        function loadUsers() {
            fetch('http://127.0.0.1:8000/api/listarUsuarios')
            .then(response => response.json())
            .then(data => {
                const select = document.getElementById('idUsuario');
                select.innerHTML = '<option value="">Seleccionar usuario</option>';
                data.forEach(user => {
                    select.innerHTML += `<option value="${user.id}">${user.Nombre} ${user.Apellido}</option>`;
                });
            })
            .catch(error => console.error('Error cargando usuarios:', error));
        }

        function loadMunicipios() {
            fetch('http://127.0.0.1:8000/api/listarMunicipios')
            .then(response => response.json())
            .then(data => {
                const select = document.getElementById('idMunicipio');
                select.innerHTML = '<option value="">Seleccionar municipio</option>';
                data.forEach(mun => {
                    select.innerHTML += `<option value="${mun.id}">${mun.Nombre_Municipio}</option>`;
                });
            })
            .catch(error => console.error('Error cargando municipios:', error));
        }

        // Funciones para gestión de categorías
        function loadCategoriesList() {
            fetch('http://127.0.0.1:8000/api/categories')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const activeTable = document.getElementById('active-categories-table');
                const inactiveTable = document.getElementById('inactive-categories-table');

                if (!Array.isArray(data) || data.length === 0) {
                    activeTable.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-gray-600">No hay categorías activas.</td></tr>';
                    inactiveTable.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-gray-600">No hay categorías inactivas.</td></tr>';
                    return;
                }

                // Separar categorías activas e inactivas
                const activeCategories = data.filter(cat => cat.estado === true || cat.estado === 1);
                const inactiveCategories = data.filter(cat => cat.estado === false || cat.estado === 0);

                // Renderizar tabla de activas
                if (activeCategories.length === 0) {
                    activeTable.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-gray-600">No hay categorías activas.</td></tr>';
                } else {
                    activeTable.innerHTML = activeCategories.map(category => `
                        <tr class="hover:bg-green-50">
                            <td class="border border-gray-300 px-4 py-2">${category.id}</td>
                            <td class="border border-gray-300 px-4 py-2 font-semibold">${category.nombre}</td>
                            <td class="border border-gray-300 px-4 py-2">${category.descripcion || 'Sin descripción'}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm">Activo</span>
                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                <button onclick="editCategory(${category.id})" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 mr-2">Editar</button>
                                <button onclick="toggleCategoryStatus(${category.id}, false)" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600 mr-2">Desactivar</button>
                                <button onclick="deleteCategory(${category.id})" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Eliminar</button>
                            </td>
                        </tr>
                    `).join('');
                }

                // Renderizar tabla de inactivas
                if (inactiveCategories.length === 0) {
                    inactiveTable.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-gray-600">No hay categorías inactivas.</td></tr>';
                } else {
                    inactiveTable.innerHTML = inactiveCategories.map(category => `
                        <tr class="hover:bg-red-50">
                            <td class="border border-gray-300 px-4 py-2">${category.id}</td>
                            <td class="border border-gray-300 px-4 py-2 font-semibold">${category.nombre}</td>
                            <td class="border border-gray-300 px-4 py-2">${category.descripcion || 'Sin descripción'}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-sm">Inactivo</span>
                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                <button onclick="editCategory(${category.id})" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 mr-2">Editar</button>
                                <button onclick="toggleCategoryStatus(${category.id}, true)" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 mr-2">Activar</button>
                                <button onclick="deleteCategory(${category.id})" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Eliminar</button>
                            </td>
                        </tr>
                    `).join('');
                }
            })
            .catch(error => {
                console.error('Error cargando categorías:', error);
                document.getElementById('active-categories-table').innerHTML = '<tr><td colspan="5" class="text-center py-4 text-red-600">Error al cargar las categorías activas.</td></tr>';
                document.getElementById('inactive-categories-table').innerHTML = '<tr><td colspan="5" class="text-center py-4 text-red-600">Error al cargar las categorías inactivas.</td></tr>';
            });
        }

        function showCreateCategoryForm() {
            document.getElementById('categoryFormTitle').textContent = 'Crear Categoría';
            document.getElementById('categoryId').value = '';
            document.getElementById('categoryNombre').value = '';
            document.getElementById('categoryDescripcion').value = '';
            document.getElementById('categoryImagen').value = '';
            document.getElementById('categoryEstado').checked = true;
            document.getElementById('categoryFormModal').classList.remove('hidden');
        }

        function editCategory(id) {
            fetch(`http://127.0.0.1:8000/api/categories/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('categoryFormTitle').textContent = 'Editar Categoría';
                document.getElementById('categoryId').value = data.id;
                document.getElementById('categoryNombre').value = data.nombre;
                document.getElementById('categoryDescripcion').value = data.descripcion || '';
                document.getElementById('categoryImagen').value = data.imagen || '';
                document.getElementById('categoryEstado').checked = data.estado;
                document.getElementById('categoryFormModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error cargando categoría:', error);
                alert('Error al cargar la categoría');
            });
        }

        function deleteCategory(id) {
            if (!confirm('¿Estás seguro de que deseas eliminar esta categoría?')) {
                return;
            }

            fetch(`http://127.0.0.1:8000/api/categories/${id}`, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Categoría eliminada exitosamente');
                    loadCategoriesList();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error eliminando categoría:', error);
                alert('Error al eliminar la categoría');
            });
        }

        function closeCategoriesModal() {
            document.getElementById('categoriesModal').classList.add('hidden');
        }

        function closeCategoryFormModal() {
            document.getElementById('categoryFormModal').classList.add('hidden');
            document.getElementById('categoryForm').reset();
        }

        // Función para cambiar el estado de una categoría (activar/desactivar)
        function toggleCategoryStatus(id, newStatus) {
            const action = newStatus ? 'activar' : 'desactivar';
            if (!confirm(`¿Estás seguro de que deseas ${action} esta categoría?`)) {
                return;
            }

            fetch(`http://127.0.0.1:8000/api/categories/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ estado: newStatus })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert(`Categoría ${newStatus ? 'activada' : 'desactivada'} exitosamente`);
                    loadCategoriesList();
                } else {
                    alert('Error: ' + JSON.stringify(result.errors || result.message));
                }
            })
            .catch(error => {
                console.error('Error cambiando estado de categoría:', error);
                alert('Error al cambiar el estado de la categoría');
            });
        }

        // Event listener para el formulario de categoría
        document.getElementById('categoryForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            data.estado = data.estado ? true : false;

            const categoryId = data.categoryId;
            const method = categoryId ? 'PUT' : 'POST';
            const url = categoryId
                ? `http://127.0.0.1:8000/api/categories/${categoryId}`
                : 'http://127.0.0.1:8000/api/categories';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert(result.message);
                    closeCategoryFormModal();
                    loadCategoriesList();
                } else {
                    alert('Error: ' + JSON.stringify(result.errors || result.message));
                }
            })
            .catch(error => {
                console.error('Error guardando categoría:', error);
                alert('Error al guardar la categoría');
            });
        });

        // Funciones para gestión de empresas
        function showCreateCompanyForm() {
            document.getElementById('empresaModalTitle').textContent = 'Crear Nueva Empresa';
            document.getElementById('empresaId').value = '';
            document.getElementById('empresaForm').reset();
            document.getElementById('empresaModal').classList.remove('hidden');
        }

        function closeEmpresaModal() {
            document.getElementById('empresaModal').classList.add('hidden');
            document.getElementById('empresaForm').reset();
        }

        function editCompany(id) {
            fetch(`http://127.0.0.1:8000/api/empresas/${id}`, {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const empresa = data.data;
                    document.getElementById('empresaModalTitle').textContent = 'Editar Empresa';
                    document.getElementById('empresaId').value = empresa.id;
                    document.getElementById('numero').value = empresa.numero;
                    document.getElementById('nombre').value = empresa.nombre;
                    document.getElementById('descripcion').value = empresa.descripcion || '';
                    document.getElementById('direccion').value = empresa.direccion;
                    document.getElementById('ciudad').value = empresa.ciudad;
                    document.getElementById('correo').value = empresa.correo;
                    document.getElementById('contraseña').value = '';
                    document.getElementById('codigo_verificacion').value = empresa.codigo_verificacion;
                    document.getElementById('telefono').value = empresa.telefono || '';
                    document.getElementById('sitio_web').value = empresa.sitio_web || '';
                    document.getElementById('politicas').value = empresa.politicas || '';
                    document.getElementById('empresaModal').classList.remove('hidden');
                } else {
                    alert('Error al cargar datos de la empresa');
                }
            })
            .catch(error => {
                console.error('Error cargando empresa:', error);
                alert('Error al cargar datos de la empresa');
            });
        }

        function viewCompanyReport(id) {
            fetch(`http://127.0.0.1:8000/api/empresas/${id}/reporte`, {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const reporte = data.data;
                    const content = document.getElementById('empresaReporteContent');
                    content.innerHTML = `
                        <div class="space-y-6">
                            <!-- Información de la Empresa -->
                            <div class="bg-blue-50 p-6 rounded-lg">
                                <h4 class="text-xl font-semibold text-blue-800 mb-4">Información de la Empresa</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p><strong>Nombre:</strong> ${reporte.empresa.nombre}</p>
                                        <p><strong>Número:</strong> ${reporte.empresa.numero}</p>
                                        <p><strong>Correo:</strong> ${reporte.empresa.correo}</p>
                                        <p><strong>Ciudad:</strong> ${reporte.empresa.ciudad}</p>
                                    </div>
                                    <div>
                                        <p><strong>Dirección:</strong> ${reporte.empresa.direccion}</p>
                                        <p><strong>Teléfono:</strong> ${reporte.empresa.telefono || 'N/A'}</p>
                                        <p><strong>Sitio Web:</strong> ${reporte.empresa.sitio_web ? `<a href="${reporte.empresa.sitio_web}" target="_blank" class="text-blue-600 underline">${reporte.empresa.sitio_web}</a>` : 'N/A'}</p>
                                        <p><strong>Fecha de Registro:</strong> ${new Date(reporte.empresa.fecha_registro).toLocaleDateString()}</p>
                                    </div>
                                </div>
                                ${reporte.empresa.descripcion ? `<p class="mt-2"><strong>Descripción:</strong> ${reporte.empresa.descripcion}</p>` : ''}
                            </div>

                            <!-- Estadísticas Generales -->
                            <div class="bg-green-50 p-6 rounded-lg">
                                <h4 class="text-xl font-semibold text-green-800 mb-4">Estadísticas Generales</h4>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-green-600">${reporte.estadisticas.total_empleados}</p>
                                        <p class="text-sm text-gray-600">Empleados</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-green-600">${reporte.estadisticas.total_actividades}</p>
                                        <p class="text-sm text-gray-600">Actividades</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-green-600">${reporte.estadisticas.total_reservas}</p>
                                        <p class="text-sm text-gray-600">Reservas</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-green-600">$${reporte.estadisticas.total_ingresos.toFixed(2)}</p>
                                        <p class="text-sm text-gray-600">Ingresos</p>
                                    </div>
                                </div>
                                <div class="mt-4 grid grid-cols-2 gap-4">
                                    <p><strong>Actividades Activas:</strong> ${reporte.estadisticas.actividades_activas}</p>
                                    <p><strong>Promedio Ocupación:</strong> ${reporte.estadisticas.promedio_ocupacion}%</p>
                                </div>
                            </div>

                            <!-- Actividades -->
                            <div class="bg-yellow-50 p-6 rounded-lg">
                                <h4 class="text-xl font-semibold text-yellow-800 mb-4">Actividades</h4>
                                <div class="overflow-x-auto">
                                    <table class="w-full table-auto border-collapse border border-gray-300 text-sm">
                                        <thead>
                                            <tr class="bg-yellow-100">
                                                <th class="border border-gray-300 px-2 py-1">Nombre</th>
                                                <th class="border border-gray-300 px-2 py-1">Fecha</th>
                                                <th class="border border-gray-300 px-2 py-1">Precio</th>
                                                <th class="border border-gray-300 px-2 py-1">Reservas</th>
                                                <th class="border border-gray-300 px-2 py-1">Ocupación</th>
                                                <th class="border border-gray-300 px-2 py-1">Ingresos</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${reporte.actividades.map(act => `
                                                <tr class="hover:bg-yellow-50">
                                                    <td class="border border-gray-300 px-2 py-1">${act.nombre}</td>
                                                    <td class="border border-gray-300 px-2 py-1">${act.fecha}</td>
                                                    <td class="border border-gray-300 px-2 py-1">$${act.precio}</td>
                                                    <td class="border border-gray-300 px-2 py-1">${act.reservas_count}</td>
                                                    <td class="border border-gray-300 px-2 py-1">${act.ocupacion_porcentaje}%</td>
                                                    <td class="border border-gray-300 px-2 py-1">$${act.ingresos.toFixed(2)}</td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Empleados -->
                            <div class="bg-purple-50 p-6 rounded-lg">
                                <h4 class="text-xl font-semibold text-purple-800 mb-4">Empleados</h4>
                                <div class="overflow-x-auto">
                                    <table class="w-full table-auto border-collapse border border-gray-300 text-sm">
                                        <thead>
                                            <tr class="bg-purple-100">
                                                <th class="border border-gray-300 px-2 py-1">Nombre</th>
                                                <th class="border border-gray-300 px-2 py-1">Email</th>
                                                <th class="border border-gray-300 px-2 py-1">Rol</th>
                                                <th class="border border-gray-300 px-2 py-1">Teléfono</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${reporte.empleados.map(emp => `
                                                <tr class="hover:bg-purple-50">
                                                    <td class="border border-gray-300 px-2 py-1">${emp.nombre}</td>
                                                    <td class="border border-gray-300 px-2 py-1">${emp.email}</td>
                                                    <td class="border border-gray-300 px-2 py-1">${emp.rol}</td>
                                                    <td class="border border-gray-300 px-2 py-1">${emp.telefono || 'N/A'}</td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Resumen Mensual -->
                            ${reporte.resumen_mensual && reporte.resumen_mensual.length > 0 ? `
                            <div class="bg-indigo-50 p-6 rounded-lg">
                                <h4 class="text-xl font-semibold text-indigo-800 mb-4">Resumen Mensual</h4>
                                <div class="overflow-x-auto">
                                    <table class="w-full table-auto border-collapse border border-gray-300 text-sm">
                                        <thead>
                                            <tr class="bg-indigo-100">
                                                <th class="border border-gray-300 px-2 py-1">Mes</th>
                                                <th class="border border-gray-300 px-2 py-1">Actividades</th>
                                                <th class="border border-gray-300 px-2 py-1">Reservas</th>
                                                <th class="border border-gray-300 px-2 py-1">Ingresos</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${reporte.resumen_mensual.map(mes => `
                                                <tr class="hover:bg-indigo-50">
                                                    <td class="border border-gray-300 px-2 py-1">${mes.mes}</td>
                                                    <td class="border border-gray-300 px-2 py-1">${mes.actividades}</td>
                                                    <td class="border border-gray-300 px-2 py-1">${mes.reservas}</td>
                                                    <td class="border border-gray-300 px-2 py-1">$${mes.ingresos.toFixed(2)}</td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            ` : ''}
                        </div>
                    `;
                    document.getElementById('empresaReporteModal').classList.remove('hidden');
                } else {
                    alert('Error al cargar el reporte: ' + (data.message || 'Error desconocido'));
                }
            })
            .catch(error => {
                console.error('Error cargando reporte:', error);
                alert('Error al cargar el reporte de la empresa');
            });
        }

        function closeEmpresaReporteModal() {
            document.getElementById('empresaReporteModal').classList.add('hidden');
            document.getElementById('empresaReporteContent').innerHTML = '';
        }

        function deleteCompany(id) {
            if (!confirm('¿Estás seguro de que quieres eliminar esta empresa? Esta acción no se puede deshacer y solo es posible si la empresa no tiene empleados asignados ni actividades con reservas.')) {
                return;
            }

            fetch(`http://127.0.0.1:8000/api/empresas/${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Empresa eliminada exitosamente');
                    loadCompanyList();
                } else {
                    let mensajeError = result.message || 'Error desconocido';
                    if (result.restricciones && result.restricciones.length > 0) {
                        mensajeError += '\n\nRestricciones:\n' + result.restricciones.join('\n');
                    }
                    alert('No se puede eliminar la empresa:\n' + mensajeError);
                }
            })
            .catch(error => {
                console.error('Error eliminando empresa:', error);
                alert('Error al eliminar la empresa');
            });
        }

        // Event listener para el formulario de empresa
        document.getElementById('empresaForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            const empresaId = data.id;
            const method = empresaId ? 'PUT' : 'POST';
            const url = empresaId
                ? `http://127.0.0.1:8000/api/empresas/${empresaId}`
                : 'http://127.0.0.1:8000/api/crearEmpresas';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success || result.id) {
                    alert(result.message || 'Empresa guardada exitosamente');
                    closeEmpresaModal();
                    loadCompanyList();
                } else {
                    alert('Error: ' + JSON.stringify(result.errors || result.message));
                }
            })
            .catch(error => {
                console.error('Error guardando empresa:', error);
                alert('Error al guardar la empresa');
            });
        });

        // Funciones para gestión de actividades de empresa
        function manageCompanyActivities(empresaId) {
            currentEmpresaId = empresaId;
            loadCompanyActivities(empresaId);
            document.getElementById('empresaActividadesModal').classList.remove('hidden');
        }

        function closeEmpresaActividadesModal() {
            document.getElementById('empresaActividadesModal').classList.add('hidden');
            document.getElementById('empresaActividadesContent').innerHTML = '';
        }

        function loadCompanyActivities(empresaId) {
            fetch(`http://127.0.0.1:8000/api/empresas/${empresaId}/actividades`, {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const content = document.getElementById('empresaActividadesContent');
                    let html = '<div class="overflow-x-auto">';
                    html += '<table class="w-full table-auto border-collapse border border-gray-300">';
                    html += '<thead><tr class="bg-gray-100"><th class="border border-gray-300 px-4 py-2">Nombre</th><th class="border border-gray-300 px-4 py-2">Fecha</th><th class="border border-gray-300 px-4 py-2">Precio</th><th class="border border-gray-300 px-4 py-2">Reservas</th><th class="border border-gray-300 px-4 py-2">Disponibilidad</th><th class="border border-gray-300 px-4 py-2">Acciones</th></tr></thead>';
                    html += '<tbody>';
                    data.data.forEach(actividad => {
                        html += `
                            <tr class="border-b">
                                <td class="px-4 py-2">${actividad.nombre}</td>
                                <td class="px-4 py-2">${actividad.fecha}</td>
                                <td class="px-4 py-2">$${actividad.precio}</td>
                                <td class="px-4 py-2">${actividad.total_reservas}</td>
                                <td class="px-4 py-2">${actividad.disponibilidad}</td>
                                <td class="px-4 py-2">
                                    <button onclick="viewCompanyActivity(${empresaId}, ${actividad.id})" class="bg-blue-500 text-white px-2 py-1 rounded text-sm mr-2">Ver</button>
                                    <button onclick="editCompanyActivity(${empresaId}, ${actividad.id})" class="bg-green-500 text-white px-2 py-1 rounded text-sm mr-2">Editar</button>
                                    <button onclick="deleteCompanyActivity(${empresaId}, ${actividad.id})" class="bg-red-500 text-white px-2 py-1 rounded text-sm">Eliminar</button>
                                </td>
                            </tr>
                        `;
                    });
                    html += '</tbody></table></div>';
                    content.innerHTML = html;
                } else {
                    alert('Error al cargar actividades: ' + (data.message || 'Error desconocido'));
                }
            })
            .catch(error => {
                console.error('Error cargando actividades:', error);
                alert('Error al cargar las actividades de la empresa');
            });
        }

        function showCreateCompanyActivityForm() {
            document.getElementById('empresaActividadModalTitle').textContent = 'Crear Nueva Actividad';
            document.getElementById('empresaActividadId').value = '';
            document.getElementById('empresaIdActividad').value = currentEmpresaId;
            document.getElementById('empresaActividadForm').reset();
            loadCategoriesForActivity();
            loadMunicipiosForActivity();
            document.getElementById('empresaActividadModal').classList.remove('hidden');
        }

        function closeEmpresaActividadModal() {
            document.getElementById('empresaActividadModal').classList.add('hidden');
            document.getElementById('empresaActividadForm').reset();
        }

        function loadCategoriesForActivity() {
            fetch('http://127.0.0.1:8000/api/categories/active')
            .then(response => response.json())
            .then(data => {
                const select = document.getElementById('empresaIdCategoria');
                select.innerHTML = '<option value="">Seleccionar categoría</option>';
                if (Array.isArray(data)) {
                    data.forEach(cat => {
                        select.innerHTML += `<option value="${cat.id}">${cat.nombre}</option>`;
                    });
                }
            })
            .catch(error => console.error('Error cargando categorías:', error));
        }

        function loadMunicipiosForActivity() {
            fetch('http://127.0.0.1:8000/api/listarMunicipios')
            .then(response => response.json())
            .then(data => {
                const select = document.getElementById('empresaIdMunicipio');
                select.innerHTML = '<option value="">Seleccionar municipio</option>';
                data.forEach(mun => {
                    select.innerHTML += `<option value="${mun.id}">${mun.Nombre_Municipio}</option>`;
                });
            })
            .catch(error => console.error('Error cargando municipios:', error));
        }

        function viewCompanyActivity(empresaId, actividadId) {
            fetch(`http://127.0.0.1:8000/api/empresas/${empresaId}/actividades/${actividadId}`, {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const actividad = data.data;
                    let detailsHtml = `
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-800 mb-2">Información Básica</h4>
                                <p><strong>Nombre:</strong> ${actividad.Nombre_Actividad}</p>
                                <p><strong>Descripción:</strong> ${actividad.Descripcion || 'N/A'}</p>
                                <p><strong>Fecha:</strong> ${actividad.Fecha_Actividad}</p>
                                <p><strong>Hora:</strong> ${actividad.Hora_Actividad}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-800 mb-2">Detalles Operativos</h4>
                                <p><strong>Precio:</strong> $${actividad.Precio}</p>
                                <p><strong>Cupo Máximo:</strong> ${actividad.Cupo_Maximo}</p>
                                <p><strong>Ubicación:</strong> ${actividad.Ubicacion}</p>
                                <p><strong>Imagen:</strong> ${actividad.Imagen ? `<a href="${actividad.Imagen}" target="_blank">Ver Imagen</a>` : 'N/A'}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg md:col-span-2">
                                <h4 class="font-semibold text-gray-800 mb-2">Relaciones</h4>
                                <p><strong>Categoría:</strong> ${actividad.categoria ? actividad.categoria.nombre : 'N/A'}</p>
                                <p><strong>Municipio:</strong> ${actividad.municipio ? actividad.municipio.Nombre_Municipio : 'N/A'}</p>
                            </div>
                        </div>
                    `;

                    // Mostrar reservas si existen
                    if (actividad.reservas && actividad.reservas.length > 0) {
                        detailsHtml += `
                            <div class="bg-yellow-50 p-4 rounded-lg mt-4">
                                <h4 class="font-semibold text-yellow-800 mb-2">Reservas (${actividad.reservas.length})</h4>
                                <div class="overflow-x-auto">
                                    <table class="w-full table-auto border-collapse border border-gray-300 text-sm">
                                        <thead>
                                            <tr class="bg-yellow-100">
                                                <th class="border border-gray-300 px-2 py-1">Usuario</th>
                                                <th class="border border-gray-300 px-2 py-1">Personas</th>
                                                <th class="border border-gray-300 px-2 py-1">Estado</th>
                                                <th class="border border-gray-300 px-2 py-1">Fecha Reserva</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${actividad.reservas.map(reserva => `
                                                <tr>
                                                    <td class="border border-gray-300 px-2 py-1">${reserva.usuario ? reserva.usuario.Nombre + ' ' + reserva.usuario.Apellido : 'N/A'}</td>
                                                    <td class="border border-gray-300 px-2 py-1">${reserva.Numero_Personas}</td>
                                                    <td class="border border-gray-300 px-2 py-1">${reserva.Estado}</td>
                                                    <td class="border border-gray-300 px-2 py-1">${reserva.Fecha_Reserva}</td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        `;
                    }

                    // Crear modal temporal para mostrar detalles
                    const modal = document.createElement('div');
                    modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
                    modal.innerHTML = `
                        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                            <div class="mt-3">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium text-gray-900">Detalles de la Actividad</h3>
                                    <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="space-y-4">${detailsHtml}</div>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(modal);
                } else {
                    alert('Error al cargar detalles de la actividad');
                }
            })
            .catch(error => {
                console.error('Error cargando actividad:', error);
                alert('Error al cargar los detalles de la actividad');
            });
        }

        function editCompanyActivity(empresaId, actividadId) {
            fetch(`http://127.0.0.1:8000/api/empresas/${empresaId}/actividades/${actividadId}`, {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const actividad = data.data;
                    document.getElementById('empresaActividadModalTitle').textContent = 'Editar Actividad';
                    document.getElementById('empresaActividadId').value = actividad.id;
                    document.getElementById('empresaIdActividad').value = empresaId;
                    document.getElementById('empresaNombreActividad').value = actividad.Nombre_Actividad;
                    document.getElementById('empresaIdCategoria').value = actividad.idCategoria || '';
                    document.getElementById('empresaIdMunicipio').value = actividad.idMunicipio || '';
                    document.getElementById('empresaFechaActividad').value = actividad.Fecha_Actividad;
                    document.getElementById('empresaHoraActividad').value = actividad.Hora_Actividad;
                    document.getElementById('empresaPrecio').value = actividad.Precio;
                    document.getElementById('empresaCupoMaximo').value = actividad.Cupo_Maximo;
                    document.getElementById('empresaUbicacion').value = actividad.Ubicacion;
                    document.getElementById('empresaDescripcion').value = actividad.Descripcion;
                    document.getElementById('empresaImagen').value = actividad.Imagen || '';
                    loadCategoriesForActivity();
                    loadMunicipiosForActivity();
                    document.getElementById('empresaActividadModal').classList.remove('hidden');
                } else {
                    alert('Error al cargar datos de la actividad');
                }
            })
            .catch(error => {
                console.error('Error cargando actividad:', error);
                alert('Error al cargar datos de la actividad');
            });
        }

        function deleteCompanyActivity(empresaId, actividadId) {
            if (!confirm('¿Estás seguro de que quieres eliminar esta actividad?')) {
                return;
            }

            fetch(`http://127.0.0.1:8000/api/empresas/${empresaId}/actividades/${actividadId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Actividad eliminada exitosamente');
                    loadCompanyActivities(empresaId);
                } else {
                    alert('Error al eliminar actividad: ' + (result.message || 'Error desconocido'));
                }
            })
            .catch(error => {
                console.error('Error eliminando actividad:', error);
                alert('Error al eliminar la actividad');
            });
        }

        // Funciones para gestión de reservas de empresa
        function manageCompanyReservations(empresaId) {
            currentEmpresaId = empresaId;
            loadCompanyReservations(empresaId);
            document.getElementById('empresaReservasModal').classList.remove('hidden');
        }

        function closeEmpresaReservasModal() {
            document.getElementById('empresaReservasModal').classList.add('hidden');
            document.getElementById('empresaReservasContent').innerHTML = '';
        }

        function loadCompanyReservations(empresaId) {
            fetch(`http://127.0.0.1:8000/api/empresas/${empresaId}/reservas`, {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const content = document.getElementById('empresaReservasContent');
                    let html = '<div class="overflow-x-auto">';
                    html += '<table class="w-full table-auto border-collapse border border-gray-300">';
                    html += '<thead><tr class="bg-gray-100"><th class="border border-gray-300 px-4 py-2">Actividad</th><th class="border border-gray-300 px-4 py-2">Usuario</th><th class="border border-gray-300 px-4 py-2">Fecha Reserva</th><th class="border border-gray-300 px-4 py-2">Personas</th><th class="border border-gray-300 px-4 py-2">Estado</th><th class="border border-gray-300 px-4 py-2">Total</th><th class="border border-gray-300 px-4 py-2">Acciones</th></tr></thead>';
                    html += '<tbody>';
                    data.data.forEach(reserva => {
                        const estadoClass = reserva.estado === 'confirmada' ? 'bg-green-100 text-green-800' :
                                          reserva.estado === 'cancelada' ? 'bg-red-100 text-red-800' :
                                          'bg-yellow-100 text-yellow-800';
                        html += `
                            <tr class="border-b">
                                <td class="px-4 py-2">${reserva.actividad.nombre}</td>
                                <td class="px-4 py-2">${reserva.usuario.nombre}</td>
                                <td class="px-4 py-2">${reserva.fecha_reserva}</td>
                                <td class="px-4 py-2">${reserva.numero_personas}</td>
                                <td class="px-4 py-2"><span class="px-2 py-1 rounded-full text-xs ${estadoClass}">${reserva.estado}</span></td>
                                <td class="px-4 py-2">$${reserva.total.toFixed(2)}</td>
                                <td class="px-4 py-2">
                                    ${reserva.estado === 'pendiente' ? `
                                        <button onclick="confirmCompanyReservation(${empresaId}, ${reserva.id})" class="bg-green-500 text-white px-2 py-1 rounded text-sm mr-2">Confirmar</button>
                                        <button onclick="cancelCompanyReservation(${empresaId}, ${reserva.id})" class="bg-red-500 text-white px-2 py-1 rounded text-sm">Cancelar</button>
                                    ` : ''}
                                </td>
                            </tr>
                        `;
                    });
                    html += '</tbody></table></div>';
                    content.innerHTML = html;
                } else {
                    alert('Error al cargar reservas: ' + (data.message || 'Error desconocido'));
                }
            })
            .catch(error => {
                console.error('Error cargando reservas:', error);
                alert('Error al cargar las reservas de la empresa');
            });
        }

        function confirmCompanyReservation(empresaId, reservaId) {
            if (!confirm('¿Confirmar esta reserva?')) {
                return;
            }

            fetch(`http://127.0.0.1:8000/api/empresas/${empresaId}/reservas/${reservaId}/confirmar`, {
                method: 'PUT',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Reserva confirmada exitosamente');
                    loadCompanyReservations(empresaId);
                } else {
                    alert('Error al confirmar reserva: ' + (result.message || 'Error desconocido'));
                }
            })
            .catch(error => {
                console.error('Error confirmando reserva:', error);
                alert('Error al confirmar la reserva');
            });
        }

        function cancelCompanyReservation(empresaId, reservaId) {
            if (!confirm('¿Cancelar esta reserva?')) {
                return;
            }

            fetch(`http://127.0.0.1:8000/api/empresas/${empresaId}/reservas/${reservaId}/cancelar`, {
                method: 'PUT',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Reserva cancelada exitosamente');
                    loadCompanyReservations(empresaId);
                } else {
                    alert('Error al cancelar reserva: ' + (result.message || 'Error desconocido'));
                }
            })
            .catch(error => {
                console.error('Error cancelando reserva:', error);
                alert('Error al cancelar la reserva');
            });
        }

        // Event listener para el formulario de actividad de empresa
        document.getElementById('empresaActividadForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            const actividadId = data.empresaActividadId;
            const empresaId = data.empresaIdActividad;
            const method = actividadId ? 'PUT' : 'POST';
            const url = actividadId
                ? `http://127.0.0.1:8000/api/empresas/${empresaId}/actividades/${actividadId}`
                : `http://127.0.0.1:8000/api/empresas/${empresaId}/actividades`;

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert(result.message || 'Actividad guardada exitosamente');
                    closeEmpresaActividadModal();
                    loadCompanyActivities(empresaId);
                } else {
                    alert('Error: ' + JSON.stringify(result.errors || result.message));
                }
            })
            .catch(error => {
                console.error('Error guardando actividad:', error);
                alert('Error al guardar la actividad');
            });
        });

        let currentEmpresaId = null;
    </script>
</body>
</html>