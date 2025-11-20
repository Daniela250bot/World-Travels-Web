<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuarios;
use App\Models\Actividades;
use App\Models\Reservas;
use App\Models\Empresa;
use App\Models\Comentarios;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportesController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

    public function getDashboardData(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'category_id' => 'nullable|integer|exists:categories,id',
            'user_id' => 'nullable|integer|exists:usuarios,id'
        ]);

        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now()->endOfMonth();
        $categoryId = $request->get('category_id');
        $userId = $request->get('user_id');

        // Usuarios registrados
        $usuariosQuery = Usuarios::whereBetween('Fecha_Registro', [$startDate, $endDate]);
        if ($userId) {
            $usuariosQuery->where('id', $userId);
        }
        $usuariosRegistrados = $usuariosQuery->count();

        // Usuarios activos (con reservas en el período)
        $usuariosActivos = Usuarios::whereHas('reservas', function($q) use ($startDate, $endDate) {
            $q->whereBetween('Fecha_Reserva', [$startDate, $endDate]);
        })->count();

        // Total actividades
        $actividadesQuery = Actividades::whereBetween('created_at', [$startDate, $endDate]);
        if ($categoryId) {
            $actividadesQuery->where('idCategoria', $categoryId);
        }
        $totalActividades = $actividadesQuery->count();

        // Total reservas
        $reservasQuery = Reservas::whereBetween('Fecha_Reserva', [$startDate, $endDate]);
        if ($categoryId) {
            $reservasQuery->whereHas('actividad', function($q) use ($categoryId) {
                $q->where('idCategoria', $categoryId);
            });
        }
        $totalReservas = $reservasQuery->count();

        // Ingresos totales
        $ingresosTotales = Reservas::whereBetween('Fecha_Reserva', [$startDate, $endDate])
            ->join('actividades', 'reservas.idActividad', '=', 'actividades.id')
            ->when($categoryId, function($q) use ($categoryId) {
                return $q->where('actividades.idCategoria', $categoryId);
            })
            ->sum(DB::raw('actividades.Precio * reservas.Numero_Personas'));

        // Empresas registradas
        $empresasRegistradas = Empresa::whereBetween('created_at', [$startDate, $endDate])->count();

        // Comentarios totales
        $comentariosTotales = Comentarios::whereBetween('created_at', [$startDate, $endDate])->count();

        // NUEVAS MÉTRICAS CLAVE
        $tasaConversion = $usuariosRegistrados > 0 ? round(($usuariosActivos / $usuariosRegistrados) * 100, 2) : 0;
        $valorPromedioReserva = $totalReservas > 0 ? round($ingresosTotales / $totalReservas, 2) : 0;
        
        // Actividad más popular
        $actividadPopular = Actividades::select('actividades.id', 'actividades.Nombre_Actividad', DB::raw('COUNT(reservas.id) as total_reservas'))
            ->leftJoin('reservas', 'actividades.id', '=', 'reservas.idActividad')
            ->whereBetween('actividades.created_at', [$startDate, $endDate])
            ->groupBy('actividades.id', 'actividades.Nombre_Actividad')
            ->orderBy('total_reservas', 'desc')
            ->first();

        // Municipio más activo
        $municipioActivo = Actividades::select('municipios.id', 'municipios.Nombre_Municipio', DB::raw('COUNT(reservas.id) as total_reservas'))
            ->leftJoin('reservas', 'actividades.id', '=', 'reservas.idActividad')
            ->leftJoin('municipios', 'actividades.idMunicipio', '=', 'municipios.id')
            ->whereBetween('actividades.created_at', [$startDate, $endDate])
            ->groupBy('municipios.id', 'municipios.Nombre_Municipio')
            ->orderBy('total_reservas', 'desc')
            ->first();

        return response()->json([
            'usuarios_registrados' => $usuariosRegistrados,
            'usuarios_activos' => $usuariosActivos,
            'total_actividades' => $totalActividades,
            'total_reservas' => $totalReservas,
            'ingresos_totales' => $ingresosTotales,
            'empresas_registradas' => $empresasRegistradas,
            'comentarios_totales' => $comentariosTotales,
            // NUEVAS MÉTRICAS CLAVE
            'tasa_conversion' => $tasaConversion,
            'valor_promedio_reserva' => $valorPromedioReserva,
            'actividad_popular' => $actividadPopular,
            'municipio_activo' => $municipioActivo,
            'periodo_analisis' => [
                'inicio' => $startDate->format('Y-m-d'),
                'fin' => $endDate->format('Y-m-d')
            ]
        ]);
    }

    public function getUsuariosChart(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if (!$startDate || !$endDate) {
            $endDate = Carbon::now();
            $startDate = $this->getStartDateForPeriod($period);
        }

        $usuarios = Usuarios::selectRaw('DATE(Fecha_Registro) as date, COUNT(*) as count')
            ->whereBetween('Fecha_Registro', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json($usuarios);
    }

    public function getReservasChart(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if (!$startDate || !$endDate) {
            $endDate = Carbon::now();
            $startDate = $this->getStartDateForPeriod($period);
        }

        $reservas = Reservas::selectRaw('DATE(Fecha_Reserva) as date, COUNT(*) as count')
            ->whereBetween('Fecha_Reserva', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json($reservas);
    }

    public function getIngresosChart(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if (!$startDate || !$endDate) {
            $endDate = Carbon::now();
            $startDate = $this->getStartDateForPeriod($period);
        }

        $ingresos = Reservas::selectRaw('DATE(reservas.Fecha_Reserva) as date, SUM(actividades.Precio * reservas.Numero_Personas) as total')
            ->join('actividades', 'reservas.idActividad', '=', 'actividades.id')
            ->whereBetween('reservas.Fecha_Reserva', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json($ingresos);
    }

    public function getCategoriasChart(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());

        $categorias = Category::select('categories.nombre', DB::raw('COUNT(DISTINCT reservas.id) as count'))
            ->leftJoin('actividades', 'categories.id', '=', 'actividades.idCategoria')
            ->leftJoin('reservas', function($join) use ($startDate, $endDate) {
                $join->on('actividades.id', '=', 'reservas.idActividad')
                     ->whereBetween('reservas.Fecha_Reserva', [$startDate, $endDate]);
            })
            ->groupBy('categories.id', 'categories.nombre')
            ->orderBy('count', 'desc')
            ->get();

        return response()->json($categorias);
    }

    // NUEVO: Método avanzado para tendencias y patrones
    public function getAdvancedTrends(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDateForPeriod($period);
        $endDate = Carbon::now();

        // Análisis de tendencias con comparaciones
        $previousStartDate = clone $startDate;
        $previousEndDate = clone $endDate;
        $daysDiff = $startDate->diffInDays($endDate);
        $previousStartDate->subDays($daysDiff);

        $currentData = $this->getPeriodData($startDate, $endDate);
        $previousData = $this->getPeriodData($previousStartDate, $previousEndDate);

        // Calcular cambios porcentuales
        $changes = [
            'usuarios' => $this->calculatePercentageChange($currentData['usuarios'], $previousData['usuarios']),
            'reservas' => $this->calculatePercentageChange($currentData['reservas'], $previousData['reservas']),
            'ingresos' => $this->calculatePercentageChange($currentData['ingresos'], $previousData['ingresos']),
            'actividades' => $this->calculatePercentageChange($currentData['actividades'], $previousData['actividades'])
        ];

        return response()->json([
            'current_period' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
                'data' => $currentData
            ],
            'previous_period' => [
                'start' => $previousStartDate->format('Y-m-d'),
                'end' => $previousEndDate->format('Y-m-d'),
                'data' => $previousData
            ],
            'changes' => $changes,
            'insights' => $this->generateInsights($currentData, $previousData, $changes)
        ]);
    }

    // NUEVO: Análisis de oportunidades de optimización
    public function getOptimizationOpportunities(Request $request)
    {
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now()->endOfMonth();

        $opportunities = [];

        // Oportunidad 1: Actividades con baja demanda
        $actividadesBajaDemanda = Actividades::select(
            'actividades.id',
            'actividades.Nombre_Actividad',
            'actividades.Precio',
            'actividades.Descripcion',
            DB::raw('COUNT(reservas.id) as total_reservas')
        )
            ->leftJoin('reservas', 'actividades.id', '=', 'reservas.idActividad')
            ->whereBetween('actividades.created_at', [$startDate, $endDate])
            ->groupBy('actividades.id', 'actividades.Nombre_Actividad', 'actividades.Precio', 'actividades.Descripcion')
            ->having('total_reservas', '<=', 2)
            ->orderBy('total_reservas', 'asc')
            ->limit(5)
            ->get();
        
        if ($actividadesBajaDemanda->count() > 0) {
            $opportunities[] = [
                'tipo' => 'marketing',
                'titulo' => 'Actividades con Baja Demanda',
                'descripcion' => 'Estas actividades tienen pocas reservas y podrían beneficiarse de campañas promocionales',
                'data' => $actividadesBajaDemanda,
                'recomendacion' => 'Considerar ofertas especiales o mejor marketing para estas actividades'
            ];
        }

        // Oportunidad 2: Horarios de mayor demanda
        $horariosDemanda = Reservas::selectRaw('HOUR(Fecha_Reserva) as hora, COUNT(*) as total')
            ->whereBetween('Fecha_Reserva', [$startDate, $endDate])
            ->groupBy('hora')
            ->orderBy('total', 'desc')
            ->get();
        
        if ($horariosDemanda->count() > 0) {
            $peakHour = $horariosDemanda->first();
            $offPeakHours = $horariosDemanda->where('total', '<', $peakHour->total * 0.3)->take(3);
            
            $opportunities[] = [
                'tipo' => 'pricing',
                'titulo' => 'Optimización de Precios por Horarios',
                'descripcion' => 'Diferentes precios según demanda de horarios',
                'peak_hour' => $peakHour,
                'off_peak_hours' => $offPeakHours,
                'recomendacion' => 'Implementar precios dinámicos: tarifas altas en horarios pico y descuentos en horarios de menor demanda'
            ];
        }

        // Oportunidad 3: Segmentación de usuarios
        $segmentacion = $this->analyzeUserSegmentation($startDate, $endDate);
        if ($segmentacion) {
            $opportunities[] = $segmentacion;
        }

        return response()->json([
            'periodo_analisis' => [
                'inicio' => $startDate->format('Y-m-d'),
                'fin' => $endDate->format('Y-m-d')
            ],
            'oportunidades' => $opportunities
        ]);
    }

    // MÉTODO EXISTENTE MEJORADO
    public function getTendencias(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDateForPeriod($period);
        $endDate = Carbon::now();

        // Tendencia de usuarios
        $usuariosTendencia = $this->calculateTrend('usuarios', 'Fecha_Registro', $startDate, $endDate);

        // Tendencia de reservas
        $reservasTendencia = $this->calculateTrend('reservas', 'Fecha_Reserva', $startDate, $endDate);

        // Tendencia de ingresos
        $ingresosTendencia = $this->calculateRevenueTrend($startDate, $endDate);

        return response()->json([
            'usuarios' => $usuariosTendencia,
            'reservas' => $reservasTendencia,
            'ingresos' => $ingresosTendencia
        ]);
    }

    // MÉTODOS AUXILIARES NUEVOS
    private function getPeriodData($startDate, $endDate)
    {
        return [
            'usuarios' => Usuarios::whereBetween('Fecha_Registro', [$startDate, $endDate])->count(),
            'reservas' => Reservas::whereBetween('Fecha_Reserva', [$startDate, $endDate])->count(),
            'ingresos' => Reservas::whereBetween('Fecha_Reserva', [$startDate, $endDate])
                ->join('actividades', 'reservas.idActividad', '=', 'actividades.id')
                ->sum(DB::raw('actividades.Precio * reservas.Numero_Personas')),
            'actividades' => Actividades::whereBetween('created_at', [$startDate, $endDate])->count()
        ];
    }

    private function calculatePercentageChange($current, $previous)
    {
        if ($previous == 0 && $current > 0) return 100;
        if ($previous == 0 && $current == 0) return 0;
        return round((($current - $previous) / $previous) * 100, 2);
    }

    private function generateInsights($currentData, $previousData, $changes)
    {
        $insights = [];

        // Insight de crecimiento
        if ($changes['usuarios'] > 10) {
            $insights[] = 'crecimiento_usuarios';
        } elseif ($changes['usuarios'] < -5) {
            $insights[] = 'declive_usuarios';
        }

        // Insight de rentabilidad
        if ($changes['ingresos'] > 15) {
            $insights[] = 'alta_rentabilidad';
        }

        // Insight de engagement
        if ($changes['reservas'] > $changes['usuarios']) {
            $insights[] = 'alto_engagement';
        }

        return $insights;
    }

    private function analyzeUserSegmentation($startDate, $endDate)
    {
        $segmentos = [];

        // Usuarios frecuentes vs esporádicos
        $usuariosFrecuentes = Usuarios::whereHas('reservas', function($q) use ($startDate, $endDate) {
            $q->whereBetween('Fecha_Reserva', [$startDate, $endDate]);
        })->withCount('reservas')->having('reservas_count', '>', 2)->get();

        if ($usuariosFrecuentes->count() > 0) {
            $segmentos[] = [
                'tipo' => 'segmentation',
                'titulo' => 'Usuarios Frecuentes',
                'descripcion' => 'Usuarios con múltiples reservas',
                'count' => $usuariosFrecuentes->count(),
                'recomendacion' => 'Crear programa de fidelidad o membresías para estos usuarios valiosos'
            ];
        }

        if (!empty($segmentos)) {
            return [
                'tipo' => 'segmentacion',
                'titulo' => 'Segmentación de Usuarios',
                'descripcion' => 'Análisis de diferentes tipos de usuarios',
                'segmentos' => $segmentos,
                'recomendacion' => 'Personalizar estrategias de marketing para cada segmento'
            ];
        }

        return null;
    }

    private function calculateTrend($table, $dateColumn, $startDate, $endDate)
    {
        $data = DB::table($table)
            ->selectRaw("DATE($dateColumn) as date, COUNT(*) as count")
            ->whereBetween($dateColumn, [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $trend = [];
        foreach ($data as $item) {
            $trend[] = [
                'date' => $item->date,
                'value' => $item->count
            ];
        }

        return $trend;
    }

    private function calculateRevenueTrend($startDate, $endDate)
    {
        $data = Reservas::selectRaw('DATE(reservas.Fecha_Reserva) as date, SUM(actividades.Precio * reservas.Numero_Personas) as total')
            ->join('actividades', 'reservas.idActividad', '=', 'actividades.id')
            ->whereBetween('reservas.Fecha_Reserva', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $trend = [];
        foreach ($data as $item) {
            $trend[] = [
                'date' => $item->date,
                'value' => (float) $item->total
            ];
        }

        return $trend;
    }

    private function getStartDateForPeriod($period)
    {
        switch ($period) {
            case 'week':
                return Carbon::now()->startOfWeek();
            case 'month':
                return Carbon::now()->startOfMonth();
            case 'quarter':
                return Carbon::now()->startOfQuarter();
            case 'year':
                return Carbon::now()->startOfYear();
            default:
                return Carbon::now()->startOfMonth();
        }
    }

    public function exportCSV(Request $request)
    {
        $type = $request->get('type', 'usuarios');
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now()->endOfMonth();

        $filename = "reporte_{$type}_" . date('Y-m-d') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($type, $startDate, $endDate) {
            $file = fopen('php://output', 'w');

            switch ($type) {
                case 'usuarios':
                    fputcsv($file, ['ID', 'Nombre', 'Apellido', 'Email', 'Fecha Registro', 'Rol']);
                    $data = Usuarios::whereBetween('Fecha_Registro', [$startDate, $endDate])->get();
                    foreach ($data as $item) {
                        fputcsv($file, [$item->id, $item->Nombre, $item->Apellido, $item->Email, $item->Fecha_Registro, $item->Rol]);
                    }
                    break;
                case 'reservas':
                    fputcsv($file, ['ID', 'Fecha Reserva', 'Numero Personas', 'Usuario', 'Actividad', 'Total']);
                    $data = Reservas::with(['usuario', 'actividad'])->whereBetween('Fecha_Reserva', [$startDate, $endDate])->get();
                    foreach ($data as $item) {
                        $total = $item->actividad ? $item->actividad->Precio * $item->Numero_Personas : 0;
                        fputcsv($file, [
                            $item->id,
                            $item->Fecha_Reserva,
                            $item->Numero_Personas,
                            $item->usuario ? $item->usuario->Nombre . ' ' . $item->usuario->Apellido : 'N/A',
                            $item->actividad ? $item->actividad->Nombre_Actividad : 'N/A',
                            $total
                        ]);
                    }
                    break;
                case 'actividades':
                    fputcsv($file, ['ID', 'Nombre', 'Fecha', 'Precio', 'Categoria', 'Municipio']);
                    $data = Actividades::with(['categoria', 'municipio'])->whereBetween('created_at', [$startDate, $endDate])->get();
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->id,
                            $item->Nombre_Actividad,
                            $item->Fecha_Actividad,
                            $item->Precio,
                            $item->categoria ? $item->categoria->nombre : 'N/A',
                            $item->municipio ? $item->municipio->Nombre_Municipio : 'N/A'
                        ]);
                    }
                    break;
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPDF(Request $request)
    {
        // Para PDF necesitaríamos una librería como DomPDF o TCPDF
        // Por simplicidad, devolveremos un mensaje
        return response()->json(['message' => 'Exportación a PDF próximamente disponible']);
    }

    public function exportExcel(Request $request)
    {
        // Similar al PDF, necesitaríamos una librería como PhpSpreadsheet
        return response()->json(['message' => 'Exportación a Excel próximamente disponible']);
    }
}