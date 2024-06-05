<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OpcionesController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\RestaurantController;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    ])->group(function () {
        Route::get('/admin/dashboard', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');
    });
    
Route::get('/csrf-token', function () {
    return response()->json(['csrfToken' => csrf_token()]);
});

Route::get('/opciones', [OpcionesController::class, 'opciones'])->middleware(['auth'])->name('opciones');

// ---------------- FORM RESERVAS -------------------

Route::get('/reservar', function() {
    return Inertia::render('Booking');
})->middleware(['auth'])->name('booking');

Route::get('/getCities', [RestaurantController::class, 'getCities']);
Route::get('/obtenerTiposDeComida', [RestaurantController::class, 'obtenerTiposDeComida']);

Route::middleware(['auth'])->group(function () {
    Route::post('/reservas', [ReservaController::class, 'store']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/mis-reservas', [ReservaController::class, 'getReservas'])->name('misreservas');
    Route::get('/reservas-guardadas', [ReservaController::class, 'getAllReservas']);

});

//chartJS
Route::get('/total-reservas', [ReservaController::class, 'getReservasGrafica']);

// ------------------ Restaurantes -------------------------------

Route::middleware(['auth'])->group(function () {
    Route::get('/api/restaurantes', [RestaurantController::class, 'filtrar']);
    Route::get('/restaurantes', [RestaurantController::class, 'mostrarVistaFiltrada']);
});

Route::get('/restaurantes-por-ciudad', [RestaurantController::class, 'getRestaurantesPorCiudad']);

// -------------------------- DASHBOARD ------------------------------- 
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/restaurants', [DashboardController::class, 'getRestaurants']);
    Route::get('/admin/users', [DashboardController::class, 'getUsers']);
    Route::get('/users', [DashboardController::class, 'getAllUsers']);
    Route::get('/restaurantesDashboard', [DashboardController::class, 'getAllRestaurants']);
});

Route::delete('/users/{userId}', [DashboardController::class, 'deleteUser']);
Route::put('/users/{userId}', [DashboardController::class, 'updateUser']);

Route::delete('/restaurantesDashboard/{restaurantId}', [DashboardController::class, 'deleteRestaurant']);

Route::get('/totals', [DashboardController::class, 'getTotals']);

Route::delete('/reservasDashboard/{reservaId}', [DashboardController::class, 'deleteReserva']);
Route::put('/reservasDashboard/{reservaId}', [DashboardController::class, 'updateReserva']);





