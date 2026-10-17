<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistroController;
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

    // HU-02: inicio de sesión
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.store');
});

// Solo usuarios autenticados. Sin sesión, 'auth' redirige a /login (CA-02.6)
Route::middleware('auth')->group(function () {
    Route::get('/panel', function () {
        return view('panel');
    })->name('panel');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
