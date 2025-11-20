<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\DepartamentosController;
use App\Http\Controllers\MunicipiosController;
use App\Http\Controllers\Categorias_ActividadesController;
use App\Http\Controllers\ActividadesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservasController;
use App\Http\Controllers\ComentariosController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\CategoryController;
use App\Http\Middleware\JwtMiddleware;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

    // Route::middleware('jwt.auth')->group(function (){});

    // Rutas de registro unificado
    Route::post('registrar', [RegistroController::class, 'registrar']);
    Route::post('verificar-administrador', [RegistroController::class, 'verificarAdministrador']);
    Route::post('verificar-empresa', [RegistroController::class, 'verificarEmpresa']);

    Route::post('login', [AuthController::class, 'login']);
    Route::post('enviar-codigo-verificacion', [AuthController::class, 'enviarCodigoVerificacion']);

    // Rutas para Administradores
    Route::post('administradores/login', [AdministradorController::class, 'login']);
    Route::post('administradores/registrar', [AdministradorController::class, 'store']);

    // Rutas para Empresas
    Route::post('empresas/login', [EmpresaController::class, 'login']);
    Route::post('empresas/registrar', [EmpresaController::class, 'store']);

    // Rutas temporalmente públicas para testing
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    // Rutas temporalmente públicas para testing
    Route::get('listarReservas', [ReservasController::class, 'index']);


// Rutas para Usuarios
Route::get('listarUsuarios', [UsuariosController::class, 'index']);
Route::post('crearUsuarios', [UsuariosController::class, 'store']);
Route::get('usuarios/{id}', [UsuariosController::class, 'show']);
Route::put('actualizarUsuarios/{id}', [UsuariosController::class, 'update']);
Route::delete('eliminarUsuarios/{id}', [UsuariosController::class, 'destroy']);

 // Rutas para Departamentos
Route::get('listarDepartamentos', [DepartamentosController::class, 'index']);
// Route::post('crearDepartamentos', [DepartamentosController::class, 'store']);
// Route::get('departamentos/{id}', [DepartamentosController::class, 'show']);
// Route::put('actualizarDepartamentos/{id}', [DepartamentosController::class, 'update']);
// Route::delete('eliminarDepartamentos/{id}', [DepartamentosController::class, 'destroy']);

// Rutas para Municipios
Route::get('listarMunicipios', [MunicipiosController::class, 'index']);
// Route::post('crearMunicipios', [MunicipiosController::class, 'store']);
// Route::get('municipios/{id}', [MunicipiosController::class, 'show']);
// Route::put('actualizarMunicipios/{id}', [MunicipiosController::class, 'update']);
// Route::delete('eliminarMunicipios/{id}', [MunicipiosController::class, 'destroy']);

// Rutas para Categorías de Actividades (antiguas)
Route::get('listarCategorias', [Categorias_ActividadesController::class, 'index']);
// Route::post('crearCategorias', [CategoriasActividadesController::class, 'store']);
// Route::get('categorias/{id}', [CategoriasActividadesController::class, 'show']);
// Route::put('actualizarCategorias/{id}', [CategoriasActividadesController::class, 'update']);
// Route::delete('eliminarCategorias/{id}', [CategoriasActividadesController::class, 'destroy']);

// Rutas para Categorías (nuevas)
Route::get('categories/active', [CategoryController::class, 'getActiveCategories']);
Route::get('categories', [CategoryController::class, 'index']);
Route::post('categories', [CategoryController::class, 'store']);
Route::get('categories/{id}', [CategoryController::class, 'show']);
Route::put('categories/{id}', [CategoryController::class, 'update']);
Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

// Rutas para Actividades
Route::get('listarActividades', [ActividadesController::class, 'index']);
Route::get('actividades/{id}', [ActividadesController::class, 'show']);

// Rutas para Reservas
// Route::get('listarReservas', [ReservasController::class, 'index']);
Route::post('crearReservas', [ReservasController::class, 'store']);
Route::get('reservas/{id}', [ReservasController::class, 'show']);
Route::put('actualizarReservas/{id}', [ReservasController::class, 'update']);
Route::delete('eliminarReservas/{id}', [ReservasController::class, 'destroy']);

// Rutas para Comentarios
Route::get('listarComentarios', [ComentariosController::class, 'index']);
Route::post('crearComentarios', [ComentariosController::class, 'store']);
Route::get('comentarios/{id}', [ComentariosController::class, 'show']);
Route::put('actualizarComentarios/{id}', [ComentariosController::class, 'update']);
Route::delete('eliminarComentarios/{id}', [ComentariosController::class, 'destroy']);

// Rutas para Empresas (públicas para dashboard)
Route::get('listarEmpresas', [EmpresaController::class, 'index']);
Route::post('crearEmpresas', [EmpresaController::class, 'store']);
Route::get('empresas/{id}', [EmpresaController::class, 'show']);
Route::put('actualizarEmpresas/{id}', [EmpresaController::class, 'update']);
Route::delete('eliminarEmpresas/{id}', [EmpresaController::class, 'destroy']);

// Rutas para Actividades (públicas para dashboard)
// Route::get('listarActividades', [ActividadesController::class, 'index']); // Duplicada, usar la de línea 89
Route::get('actividades/{id}', [ActividadesController::class, 'show']);

// Rutas para Departamentos (públicas para dashboard)
Route::get('departamentos', [DepartamentosController::class, 'index']);

// Rutas para Municipios (públicas para dashboard)
Route::get('listarMunicipios', [MunicipiosController::class, 'index']);
Route::post('crearMunicipios', [MunicipiosController::class, 'store']);
Route::get('municipios/{id}', [MunicipiosController::class, 'show']);
Route::put('actualizarMunicipios/{id}', [MunicipiosController::class, 'update']);
// Rutas para Reportes
Route::get('reportes/dashboard-data', [ReportesController::class, 'getDashboardData']);
Route::get('reportes/usuarios-chart', [ReportesController::class, 'getUsuariosChart']);
Route::get('reportes/reservas-chart', [ReportesController::class, 'getReservasChart']);
Route::get('reportes/ingresos-chart', [ReportesController::class, 'getIngresosChart']);
Route::get('reportes/categorias-chart', [ReportesController::class, 'getCategoriasChart']);
Route::get('reportes/tendencias', [ReportesController::class, 'getTendencias']);
// NUEVAS RUTAS AVANZADAS
Route::get('reportes/advanced-trends', [ReportesController::class, 'getAdvancedTrends']);
Route::get('reportes/optimization-opportunities', [ReportesController::class, 'getOptimizationOpportunities']);
Route::get('reportes/export-csv', [ReportesController::class, 'exportCSV']);
Route::get('reportes/export-pdf', [ReportesController::class, 'exportPDF']);
Route::get('reportes/export-excel', [ReportesController::class, 'exportExcel']);
Route::delete('eliminarMunicipios/{id}', [MunicipiosController::class, 'destroy']);

// Rutas protegidas para Empresas (sin restricciones adicionales)
Route::group(['middleware' => [JwtMiddleware::class]], function () {
    // Rutas para empresas gestionando sus propias actividades y reservas
    Route::get('empresas/me', [EmpresaController::class, 'me']);
    Route::post('empresas/logout', [EmpresaController::class, 'logout']);

    // Gestión de actividades por empresa (para dashboard de empresa)
    Route::get('empresas/actividades', [EmpresaController::class, 'listarActividades']);
    Route::post('empresas/actividades', [EmpresaController::class, 'crearActividad']);
    Route::get('empresas/actividades/{actividadId}', [EmpresaController::class, 'verActividad']);
    Route::put('empresas/actividades/{actividadId}', [EmpresaController::class, 'actualizarActividad']);
    Route::delete('empresas/actividades/{actividadId}', [EmpresaController::class, 'eliminarActividad']);

    // Rutas estándar de actividades para empresas (con validación de propiedad)
    Route::post('empresas/crearActividades', [EmpresaController::class, 'crearActividadEmpresa']);
    Route::put('empresas/actualizarActividades/{id}', [EmpresaController::class, 'actualizarActividadEmpresa']);
    Route::delete('empresas/eliminarActividades/{id}', [EmpresaController::class, 'eliminarActividadEmpresa']);

    // Gestión de reservas por empresa
    Route::get('empresas/reservas', [EmpresaController::class, 'listarReservas']);
    Route::put('empresas/reservas/{reservaId}/confirmar', [EmpresaController::class, 'confirmarReserva']);
    Route::put('empresas/reservas/{reservaId}/cancelar', [EmpresaController::class, 'cancelarReserva']);
});

// Rutas protegidas para Administradores
Route::group(['middleware' => [JwtMiddleware::class]], function () {
    // CRUD Administradores con permisos específicos
    Route::get('administradores', [AdministradorController::class, 'index'])->middleware('check.permission:ver_administradores');
    Route::get('administradores/{id}', [AdministradorController::class, 'show'])->middleware('check.permission:ver_administradores');
    Route::put('administradores/{id}', [AdministradorController::class, 'update'])->middleware('check.permission:editar_administradores');
    Route::delete('administradores/{id}', [AdministradorController::class, 'destroy'])->middleware('check.permission:eliminar_administradores');
    Route::post('administradores/logout', [AdministradorController::class, 'logout']);
    Route::get('administradores/me', [AdministradorController::class, 'me']);

    // CRUD Empresas con permisos
    Route::get('empresas', [EmpresaController::class, 'index'])->middleware('check.permission:gestionar_empresas');
    Route::get('empresas/{id}', [EmpresaController::class, 'show'])->middleware('check.permission:gestionar_empresas');
    Route::put('empresas/{id}', [EmpresaController::class, 'update'])->middleware('check.permission:gestionar_empresas');
    Route::delete('empresas/{id}', [EmpresaController::class, 'destroy'])->middleware('check.permission:gestionar_empresas');

    // Rutas adicionales para gestión de empresas desde panel administrador
    Route::post('empresas/{empresaId}/asignar-empleado', [EmpresaController::class, 'asignarEmpleado'])->middleware('check.permission:gestionar_empresas');
    Route::get('empresas/{empresaId}/empleados', [EmpresaController::class, 'empleados'])->middleware('check.permission:gestionar_empresas');
    Route::get('empresas/{empresaId}/reporte', [EmpresaController::class, 'reporte'])->middleware('check.permission:gestionar_empresas');

    // CRUD Actividades protegidas
    Route::post('crearActividades', [ActividadesController::class, 'store']);
    Route::put('actualizarActividades/{id}', [ActividadesController::class, 'update']);
    Route::delete('eliminarActividades/{id}', [ActividadesController::class, 'destroy']);

});
