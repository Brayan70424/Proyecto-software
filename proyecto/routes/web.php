<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RecuperarContrasenaController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\TareaController;
use App\Http\Middleware\NoCacheHeaders;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('panel')
        : redirect()->route('login');
});


Route::middleware('guest')->group(function () {
    
    Route::get('/registro', [RegistroController::class, 'create'])->name('registro');
    Route::post('/registro', [RegistroController::class, 'store'])->name('registro.store');

    
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.store');

    
    Route::get('/olvide-contrasena', [RecuperarContrasenaController::class, 'formularioSolicitud'])->name('password.request');
    Route::post('/olvide-contrasena', [RecuperarContrasenaController::class, 'enviarEnlace'])->name('password.email');
    Route::get('/restablecer-contrasena/{token}', [RecuperarContrasenaController::class, 'formularioRestablecer'])->name('password.reset');
    Route::post('/restablecer-contrasena', [RecuperarContrasenaController::class, 'restablecer'])->name('password.update');
});



Route::middleware(['auth', NoCacheHeaders::class])->group(function () {
    
    Route::get('/panel', function () {
        return view('panel');
    })->name('panel');

    
    Route::get('/tareas', function () { return view('tareas'); })->name('tareas');
    Route::get('/api/tareas', [TareaController::class, 'index'])->name('api.tareas.index');
    Route::get('/api/tareas/{id}', [TareaController::class, 'show'])->whereNumber('id')->name('api.tareas.show');
    Route::post('/api/tareas',[TareaController::class, 'store'])->name('api.tareas.store');
    Route::put('/api/tareas/{id}', [TareaController::class, 'update'])->whereNumber('id')->name('api.tareas.update');
    Route::delete('/api/tareas/{id}', [TareaController::class, 'destroy'])->whereNumber('id')->name('api.tareas.destroy');

    
    Route::get('/calendario', function () {
        return view('calendario');
    })->name('calendario');

    
    Route::post('/logout', LogoutController::class)->name('logout');
});