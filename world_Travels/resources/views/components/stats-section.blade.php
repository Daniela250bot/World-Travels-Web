<!-- Panel de Control Rápido -->
<div class="grid grid-cols-1 xl:grid-cols-5 gap-6 lg:gap-8 mb-8 lg:mb-12">
    <!-- Estadísticas rápidas -->
    <div class="xl:col-span-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6 xl:gap-8" id="stats-section">
        <!-- Estadísticas se cargarán aquí -->
    </div>

    <!-- Acciones Rápidas -->
    <div class="xl:col-span-2 bg-white rounded-2xl lg:rounded-3xl shadow-xl p-6 lg:p-8 border border-gray-100 hover:shadow-2xl transition-shadow duration-300">
        <h3 class="text-lg lg:text-xl font-bold text-gray-800 mb-4 lg:mb-6 flex items-center">
            <svg class="w-5 h-5 lg:w-6 lg:h-6 mr-2 lg:mr-3 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            <span class="truncate">Acciones Rápidas</span>
        </h3>
        <div class="space-y-3 lg:space-y-4">
            @yield('quick-actions')
        </div>
    </div>
</div>