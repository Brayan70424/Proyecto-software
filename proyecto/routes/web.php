<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RecuperarContrasenaController;
use App\Http\Controllers\RegistroController;
use App\Http\Middleware\NoCacheHeaders;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Raíz: envía al panel si hay sesión, o al login si no
Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('panel')
        : redirect()->route('login');
});

// Solo visitantes (sin sesión). Si ya hay sesión, 'guest' redirige a /panel
Route::middleware('guest')->group(function () {
    // HU-01: registro
    Route::get('/registro', [RegistroController::class, 'create'])->name('registro');
    Route::post('/registro', [RegistroController::class, 'store'])->name('registro.store');

    // HU-02 y HU-05: inicio de sesión con límite de intentos fallidos
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.store');

    // HU-03: recuperación de contraseña
    Route::get('/olvide-contrasena', [RecuperarContrasenaController::class, 'formularioSolicitud'])->name('password.request');
    Route::post('/olvide-contrasena', [RecuperarContrasenaController::class, 'enviarEnlace'])->name('password.email');
    Route::get('/restablecer-contrasena/{token}', [RecuperarContrasenaController::class, 'formularioRestablecer'])->name('password.reset');
    Route::post('/restablecer-contrasena', [RecuperarContrasenaController::class, 'restablecer'])->name('password.update');
});

// Solo usuarios autenticados. Sin sesión, 'auth' redirige a /login (HU-02 · CA-02.6)
// NoCacheHeaders impide que el botón "Atrás" muestre páginas privadas (HU-04 · CA-04.4)
Route::middleware(['auth', NoCacheHeaders::class])->group(function () {
    Route::get('/panel', function () {
        return view('panel');
    })->name('panel');

    // HU-04: cerrar sesión
    Route::post('/logout', LogoutController::class)->name('logout');
});
