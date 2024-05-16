<?php

use App\Http\Controllers\OpcionesController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\RestaurantController;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::get('/opciones', [OpcionesController::class, 'opciones'])->middleware(['auth'])->name('opciones');

// ---------------- FORM RESERVAS -------------------

Route::get('/reservar', function() {
    return Inertia::render('Booking');
})->middleware(['auth'])->name('booking');

Route::get('/getCities', [RestaurantController::class, 'getCities']);

Route::get('/obtenerTiposDeComida', [RestaurantController::class, 'obtenerTiposDeComida']);

Route::post('/reservas', [ReservaController::class, 'store']);

