<?php

use App\Http\Middleware\CheckRole;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ElementoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VigilanteController;

// Rutas para autenticación y registro
Route::get('/', [WelcomeController::class, 'index']);
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::get('create', [AuthController::class, 'create'])->name('create');
Route::post('registrado', [AuthController::class, 'createpost'])->name('createpost');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Agrupación de rutas protegidas con autenticación 
Route::middleware('auth')->group(function () {

    // Ruta para actualizar el perfil
    Route::post('/update-profile', [AuthController::class, 'updateProfile'])->name('updateProfile');

    // Rutas para la gestión de elementos
    Route::get('elementos/create', [ElementoController::class, 'create'])->name('elementos.create');
    Route::post('/elementos', [ElementoController::class, 'store'])->name('elementos.store');
    Route::delete('/elementos/{id}', [ElementoController::class, 'destroy'])->name('elementos.destroy');
    Route::get('/elementos/{id}/edit', [ElementoController::class, 'edit'])->name('elementos.edit');
    Route::put('/elementos/{id}', [ElementoController::class, 'update'])->name('elementos.update');

    // Rutas para los paneles de administración, control y usuario
    Route::middleware(CheckRole::class . ':1')->group(function () {
        Route::get('admin/panel', function () {
            return view('index.vistaadmin');
        })->name('admin.panel');
    });

    // Solo rol 2 puede acceder al panel de control
    Route::middleware(CheckRole::class . ':2')->group(function () {
        Route::get('control/panel', function () {
            return view('index.vistacontrol');
        })->name('control.panel');
    });

    // Ruta para buscar por documento en la vista del vigilante (solo rol 2)
    Route::middleware(CheckRole::class . ':2')->group(function () {
        Route::get('/vigilante/buscar', [VigilanteController::class, 'buscarPorDocumento'])->name('vigilante.buscar');
    });

    // Panel de usuario (roles 3, 4 y 5)
    Route::middleware(CheckRole::class . ':3,4,5')->group(function () {
        Route::get('user/panel', [UserController::class, 'userPanel'])->name('user.panel');
    });

    // Rutas generales
    Route::get('/editProfile', [AuthController::class, 'showEditProfile'])->name('editProfile');
    Route::get('/usuario/{id}', [UserController::class, 'show'])->name('user.show');
    Route::get('/usuario/panel', [UserController::class, 'mostrarPerfil']);
    Route::get('/perfil', [UserController::class, 'mostrarPerfil'])->name('perfil');
});

Route::middleware(['auth', 'checkRole:admin'])->group(function () {
    // Rutas que solo pueden acceder los usuarios con el rol de 'admin'
});