<?php
use Illuminate\Support\Facades\Route;

// Dashboard estudiante: lateral con solicitud e historial
Route::get('/dashboard-estudiante', [App\Http\Controllers\DashboardController::class, 'estudiante'])
    ->name('dashboard.estudiante')
    ->middleware(['auth']);

// Dashboard administrador: lateral con autorizaciones
Route::get('/dashboard-admin', function() {
    return view('dashboard.admin');
})->name('dashboard.admin')->middleware(['auth']);
// Ruta para la pantalla de autorización (administrador)
Route::get('/dashboard-autorizar', [App\Http\Controllers\DashboardController::class, 'autorizar'])
    ->name('dashboard.autorizar')
    ->middleware([\App\Http\Middleware\Authenticate::class, \App\Http\Middleware\RolMiddleware::class]);
// Ruta para reportes de préstamos
Route::get('reportes', [App\Http\Controllers\ReporteController::class, 'index'])
    ->name('reportes.index')
    ->middleware([\App\Http\Middleware\Authenticate::class, \App\Http\Middleware\RolMiddleware::class]);

// Rutas para autorizar/rechazar solicitudes (solo para administradores)
Route::post('reportes/{id}/aprobar', [App\Http\Controllers\AutorizacionController::class, 'aprobar'])
    ->name('reportes.aprobar')
    ->middleware([\App\Http\Middleware\Authenticate::class, \App\Http\Middleware\RolMiddleware::class]);

Route::post('reportes/{id}/rechazar', [App\Http\Controllers\AutorizacionController::class, 'rechazar'])
    ->name('reportes.rechazar')
    ->middleware([\App\Http\Middleware\Authenticate::class, \App\Http\Middleware\RolMiddleware::class]);


// Login y logout
Route::get('/', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Registro
Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register.post');

// Rutas para solicitudes (solo estudiantes pueden crear, admins pueden ver todas)
Route::middleware([\App\Http\Middleware\Authenticate::class, \App\Http\Middleware\RolMiddleware::class])->group(function () {
    // Excluir rutas de edición (edit/update) porque la UI no permite editar solicitudes
    Route::resource('solicitudes', App\Http\Controllers\SolicitudController::class)->except(['destroy', 'edit', 'update']);

    // Rutas para solicitudes de kit (Arduino)
    Route::get('solicitudes-kit', [App\Http\Controllers\SolicitudKitController::class, 'index'])->name('solicitudes.kit.index');
    Route::get('solicitudes-kit/create', [App\Http\Controllers\SolicitudKitController::class, 'create'])->name('solicitudes.kit.create');
    Route::post('solicitudes-kit', [App\Http\Controllers\SolicitudKitController::class, 'store'])->name('solicitudes.kit.store');
    Route::get('solicitudes-kit/{id}', [App\Http\Controllers\SolicitudKitController::class, 'show'])->name('solicitudes.kit.show');

    // Endpoint para obtener componentes de un kit (JSON) - usado por el formulario dinámico
    Route::get('kits/{id}/componentes', [App\Http\Controllers\KitController::class, 'componentes'])->name('kits.componentes');

    // Autorizar / rechazar kits (admin)
    Route::post('reportes/kit/{id}/aprobar', [App\Http\Controllers\AutorizacionKitController::class, 'aprobar'])->name('reportes.kit.aprobar');
    Route::post('reportes/kit/{id}/rechazar', [App\Http\Controllers\AutorizacionKitController::class, 'rechazar'])->name('reportes.kit.rechazar');

    // Páginas de administración para kits
    Route::get('/dashboard-autorizarkit', [App\Http\Controllers\DashboardController::class, 'autorizarKits'])
        ->name('dashboard.autorizar.kits')
        ->middleware([\App\Http\Middleware\Authenticate::class, \App\Http\Middleware\RolMiddleware::class]);

    Route::get('/dashboard-reportes-kits', [App\Http\Controllers\DashboardController::class, 'reportesKits'])
        ->name('dashboard.reportes.kits')
        ->middleware([\App\Http\Middleware\Authenticate::class, \App\Http\Middleware\RolMiddleware::class]);
});
