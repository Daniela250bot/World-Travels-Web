<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Avanzados - WORLD TRAVELS</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/date-fns@2.29.3/index.min.js"></script>
    <style>
        .metric-card {
            transition: all 0.3s ease;
        }
        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .trend-positive { color: #10b981; }
        .trend-negative { color: #ef4444; }
        .trend-neutral { color: #6b7280; }
        .opportunity-card {
            border-left: 4px solid #3b82f6;
        }
        .insight-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .chart-container {
            position: relative;
            height: 300px;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
    </style>
</head>
<body class="bg-gray-50">
    <header class="bg-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-lg">WT</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        WORLD TRAVELS Analytics
                    </h1>
                    <p class="text-sm text-gray-500">Panel de Inteligencia de Negocios</p>
                </div>
            </div>
            <nav class="space-x-6">
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 transition">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="text-gray-700 hover:text-blue-600 transition bg-transparent border-none cursor-pointer">Cerrar Sesión</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <!-- Header con filtros mejorados -->
        <div class="mb-8 fade-in">
            <div class="text-center mb-6">
                <h2 class="text-4xl font-bold mb-2 text-gray-800">Centro de Análisis y Tendencias</h2>
                <p class="text-gray-600">Insights accionables para optimizar el rendimiento del negocio</p>
            </div>
            
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
                <h3 class="text-xl font-bold mb-6 text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filtros de Análisis
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Inicio</label>
                        <input type="date" id="start_date" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Fin</label>
                        <input type="date" id="end_date" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                        <select id="category_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todas las categorías</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Período</label>
                        <select id="period" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="week">Semana</option>
                            <option value="month" selected>Mes</option>
                            <option value="quarter">Trimestre</option>
                            <option value="year">Año</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button onclick="applyFilters()" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-lg hover:from-blue-700 hover:to-purple-700 transition">
                            Analizar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Métricas Clave Mejoradas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" id="key-metrics-section">
            <!-- Métricas se cargarán aquí -->
        </div>

        <!-- Insights y Tendencias Principales -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Análisis Comparativo -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-xl p-6 fade-in">
                <h3 class="text-xl font-bold mb-6 text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    Análisis Comparativo de Tendencias
                </h3>
                <div class="chart-container">
                    <canvas id="advancedTrendsChart"></canvas>
                </div>
            </div>

            <!-- Insights Clave -->
            <div class="bg-white rounded-2xl shadow-xl p-6 fade-in">
                <h3 class="text-xl font-bold mb-6 text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                    Insights Clave
                </h3>
                <div id="insights-section" class="space-y-4">
                    <!-- Insights se cargarán aquí -->
                </div>
            </div>
        </div>

        <!-- Patrones de Uso Detallados -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Patrones de Registro de Usuarios -->
            <div class="bg-white rounded-2xl shadow-xl p-6 fade-in">
                <h3 class="text-xl font-bold mb-4 text-gray-800">Patrones de Registro de Usuarios</h3>
                <div class="chart-container">
                    <canvas id="usuariosPatternChart"></canvas>
                </div>
                <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-800" id="usuariosPatternInsight"></p>
                </div>
            </div>

            <!-- Patrones de Reservas por Horario -->
            <div class="bg-white rounded-2xl shadow-xl p-6 fade-in">
                <h3 class="text-xl font-bold mb-4 text-gray-800">Distribución de Reservas por Hora</h3>
                <div class="chart-container">
                    <canvas id="reservasHorarioChart"></canvas>
                </div>
                <div class="mt-4 p-4 bg-green-50 rounded-lg">
                    <p class="text-sm text-green-800" id="reservasHorarioInsight"></p>
                </div>
            </div>
        </div>

        <!-- Oportunidades de Optimización -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 fade-in">
            <h3 class="text-xl font-bold mb-6 text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Oportunidades de Optimización
            </h3>
            <div id="optimization-opportunities" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Oportunidades se cargarán aquí -->
            </div>
        </div>

        <!-- Análisis de Categorías y Municipios -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Popularidad por Categoría -->
            <div class="bg-white rounded-2xl shadow-xl p-6 fade-in">
                <h3 class="text-xl font-bold mb-4 text-gray-800">Popularidad por Categoría</h3>
                <div class="chart-container">
                    <canvas id="categoriaPopularidadChart"></canvas>
                </div>
            </div>

            <!-- Actividad Geográfica -->
            <div class="bg-white rounded-2xl shadow-xl p-6 fade-in">
                <h3 class="text-xl font-bold mb-4 text-gray-800">Actividad por Municipio</h3>
                <div class="chart-container">
                    <canvas id="municipioActividadChart"></canvas>
                </div>
                <div class="mt-4 p-4 bg-purple-50 rounded-lg">
                    <p class="text-sm text-purple-800" id="municipioInsight"></p>
                </div>
            </div>
        </div>

        <!-- Dashboard de Exportación Mejorado -->
        <div class="bg-white rounded-2xl shadow-xl p-6 fade-in">
            <h3 class="text-xl font-bold mb-6 text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Exportación y Reportes Avanzados
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <button onclick="exportData('csv', 'advanced-trends')" class="w-full bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition mb-2">
                        Exportar Tendencias CSV
                    </button>
                    <p class="text-sm text-gray-600">Análisis comparativo completo</p>
                </div>
                <div class="text-center">
                    <button onclick="exportData('csv', 'optimization')" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition mb-2">
                        Exportar Oportunidades CSV
                    </button>
                    <p class="text-sm text-gray-600">Recomendaciones de optimización</p>
                </div>
                <div class="text-center">
                    <button onclick="generateExecutiveReport()" class="w-full bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition mb-2">
                        Reporte Ejecutivo PDF
                    </button>
                    <p class="text-sm text-gray-600">Resumen ejecutivo completo</p>
                </div>
            </div>
        </div>
    </main>

    <script>
        let advancedTrendsChart, usuariosPatternChart, reservasHorarioChart, categoriaPopularidadChart, municipioActividadChart;

        document.addEventListener('DOMContentLoaded', function() {
            console.log('Iniciando Centro de Análisis...');
            loadFilters();
            loadAdvancedDashboardData();
            loadAdvancedTrends();
            loadOptimizationOpportunities();
            setDefaultDates();
        });

        function setDefaultDates() {
            const today = new Date();
            const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            
            document.getElementById('start_date').value = firstDayOfMonth.toISOString().split('T')[0];
            document.getElementById('end_date').value = today.toISOString().split('T')[0];
        }

        function loadFilters() {
            // Cargar categorías
            fetch('/api/categories', {
                headers: {
                    'Authorization': 'Bearer ' + (localStorage.getItem('token') || ''),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const select = document.getElementById('category_id');
                data.forEach(cat => {
                    select.innerHTML += `<option value="${cat.id}">${cat.nombre}</option>`;
                });
            })
            .catch(error => console.error('Error cargando categorías:', error));
        }

        function loadAdvancedDashboardData() {
            const params = getFilterParams();
            fetch(`/api/reportes/dashboard-data?${params}`, {
                headers: {
                    'Authorization': 'Bearer ' + (localStorage.getItem('token') || ''),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                displayKeyMetrics(data);
                displayActivityInsights(data);
            })
            .catch(error => {
                console.error('Error cargando datos:', error);
                // Mostrar error amigable
                document.getElementById('key-metrics-section').innerHTML = `
                    <div class="col-span-4 text-center py-8">
                        <p class="text-gray-500">Error cargando datos. Verifica tu autenticación.</p>
                    </div>
                `;
            });
        }

        function displayKeyMetrics(data) {
            const metricsSection = document.getElementById('key-metrics-section');
            
            const formatCurrency = (value) => `$${new Intl.NumberFormat('es-CO').format(value || 0)}`;
            const formatPercentage = (value) => `${value || 0}%`;
            
            metricsSection.innerHTML = `
                <div class="metric-card bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-2xl shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-3xl font-bold">${data.usuarios_registrados || 0}</h3>
                            <p class="text-blue-100">Usuarios Registrados</p>
                        </div>
                        <div class="bg-blue-400 p-3 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="metric-card bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-2xl shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-3xl font-bold">${formatPercentage(data.tasa_conversion)}</h3>
                            <p class="text-green-100">Tasa de Conversión</p>
                        </div>
                        <div class="bg-green-400 p-3 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="metric-card bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-2xl shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-3xl font-bold">${formatCurrency(data.valor_promedio_reserva)}</h3>
                            <p class="text-purple-100">Valor Promedio Reserva</p>
                        </div>
                        <div class="bg-purple-400 p-3 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="metric-card bg-gradient-to-r from-yellow-500 to-orange-500 text-white p-6 rounded-2xl shadow-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-3xl font-bold">${data.total_reservas || 0}</h3>
                            <p class="text-yellow-100">Total Reservas</p>
                        </div>
                        <div class="bg-yellow-400 p-3 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            `;
        }

        function displayActivityInsights(data) {
            const insightsSection = document.getElementById('insights-section');
            
            let insights = [];
            
            // Insight de actividad popular
            if (data.actividad_popular) {
                insights.push({
                    type: 'success',
                    title: 'Actividad Estrella',
                    description: `"${data.actividad_popular.Nombre_Actividad}" es la más solicitada con ${data.actividad_popular.total_reservas} reservas`,
                    icon: 'star'
                });
            }

            // Insight de municipio activo
            if (data.municipio_activo) {
                insights.push({
                    type: 'info',
                    title: 'Destino Popular',
                    description: `${data.municipio_activo.Nombre_Municipio} lidera en actividad turística`,
                    icon: 'map'
                });
            }

            // Insight de conversión
            if (data.tasa_conversion > 50) {
                insights.push({
                    type: 'success',
                    title: 'Excelente Conversión',
                    description: `Alta tasa de conversión del ${data.tasa_conversion}% indica usuarios muy activos`,
                    icon: 'trending-up'
                });
            } else if (data.tasa_conversion < 20) {
                insights.push({
                    type: 'warning',
                    title: 'Oportunidad de Mejora',
                    description: `Baja tasa de conversión del ${data.tasa_conversion}% - se requiere estrategia de activación`,
                    icon: 'warning'
                });
            }

            // Renderizar insights
            insightsSection.innerHTML = insights.map(insight => `
                <div class="bg-${insight.type === 'success' ? 'green' : insight.type === 'warning' ? 'yellow' : 'blue'}-50 border-l-4 border-${insight.type === 'success' ? 'green' : insight.type === 'warning' ? 'yellow' : 'blue'}-400 p-4 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-${insight.type === 'success' ? 'green' : insight.type === 'warning' ? 'yellow' : 'blue'}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-${insight.type === 'success' ? 'green' : insight.type === 'warning' ? 'yellow' : 'blue'}-800">${insight.title}</h4>
                            <p class="text-sm text-${insight.type === 'success' ? 'green' : insight.type === 'warning' ? 'yellow' : 'blue'}-700 mt-1">${insight.description}</p>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function loadAdvancedTrends() {
            const period = document.getElementById('period').value;
            fetch(`/api/reportes/advanced-trends?period=${period}`, {
                headers: {
                    'Authorization': 'Bearer ' + (localStorage.getItem('token') || ''),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                displayAdvancedTrendsChart(data);
                displayInsights(data);
            })
            .catch(error => console.error('Error cargando tendencias avanzadas:', error));
        }

        function displayAdvancedTrendsChart(data) {
            const ctx = document.getElementById('advancedTrendsChart').getContext('2d');
            if (advancedTrendsChart) advancedTrendsChart.destroy();

            const currentPeriod = data.current_period.data;
            const previousPeriod = data.previous_period.data;

            advancedTrendsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Usuarios', 'Reservas', 'Ingresos', 'Actividades'],
                    datasets: [{
                        label: 'Período Actual',
                        data: [currentPeriod.usuarios, currentPeriod.reservas, currentPeriod.ingresos, currentPeriod.actividades],
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4
                    }, {
                        label: 'Período Anterior',
                        data: [previousPeriod.usuarios, previousPeriod.reservas, previousPeriod.ingresos, previousPeriod.actividades],
                        borderColor: 'rgb(156, 163, 175)',
                        backgroundColor: 'rgba(156, 163, 175, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function displayInsights(data) {
            // Implementar insights basados en los datos de tendencias
            console.log('Insights de tendencias:', data.insights);
        }

        function loadOptimizationOpportunities() {
            const params = getFilterParams();
            fetch(`/api/reportes/optimization-opportunities?${params}`, {
                headers: {
                    'Authorization': 'Bearer ' + (localStorage.getItem('token') || ''),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                displayOptimizationOpportunities(data.oportunidades);
            })
            .catch(error => console.error('Error cargando oportunidades:', error));
        }

        function displayOptimizationOpportunities(opportunities) {
            const container = document.getElementById('optimization-opportunities');
            
            if (!opportunities || opportunities.length === 0) {
                container.innerHTML = `
                    <div class="col-span-2 text-center py-8">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-500">No se encontraron oportunidades específicas en el período seleccionado.</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = opportunities.map(opportunity => `
                <div class="opportunity-card bg-white border border-gray-200 rounded-xl p-6 shadow-lg">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800">${opportunity.titulo}</h4>
                                <span class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full mt-1">
                                    ${opportunity.tipo}
                                </span>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">${opportunity.descripcion}</p>
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h5 class="font-medium text-blue-900 mb-2">Recomendación:</h5>
                        <p class="text-sm text-blue-800">${opportunity.recomendacion}</p>
                    </div>
                </div>
            `).join('');
        }

        function getFilterParams() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const categoryId = document.getElementById('category_id').value;

            let params = [];
            if (startDate) params.push(`start_date=${encodeURIComponent(startDate)}`);
            if (endDate) params.push(`end_date=${encodeURIComponent(endDate)}`);
            if (categoryId) params.push(`category_id=${encodeURIComponent(categoryId)}`);

            return params.join('&');
        }

        function applyFilters() {
            loadAdvancedDashboardData();
            loadAdvancedTrends();
            loadOptimizationOpportunities();
        }

        function exportData(format, type = 'advanced-trends') {
            const params = getFilterParams();
            window.open(`/api/reportes/export-${format}?type=${type}&${params}`, '_blank');
        }

        function generateExecutiveReport() {
            alert('Generando reporte ejecutivo... (Funcionalidad en desarrollo)');
        }
    </script>
</body>
</html>