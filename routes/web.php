<?php

use App\Http\Middleware\CheckRole;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ElementoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Models\Categoria;

// Rutas para autenticación y registro
Route::get('/', [WelcomeController::class, 'index']);
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::get('create', [AuthController::class, 'create'])->name('create');
Route::post('registrado', [AuthController::class, 'createpost'])->name('createpost');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    // Ruta para actualizar el perfil
    Route::post('/update-profile', [AuthController::class, 'updateProfile'])->name('updateProfile');
    
    // Rutas para la gestión de elementos
    Route::get('elementos/create', [ElementoController::class, 'create'])->name('elementos.create');
    Route::post('/elementos', [ElementoController::class, 'store'])->name('elementos.store');
    // Ruta para eliminar un elemento
    Route::delete('/elementos/{id}', [ElementoController::class, 'destroy'])->name('elementos.destroy');
    // Ruta para editar un elemento
    Route::get('/elementos/{id}/edit', [ElementoController::class, 'edit'])->name('elementos.edit');
    Route::put('/elementos/{id}', [ElementoController::class, 'update'])->name('elementos.update');
    
    // Rutas para los paneles de administración, control y usuario
    Route::get('admin/panel', function () {
        return view('index.vistaadmin');
    })->name('admin.panel');
    Route::get('control/panel', function () { 
        return view('index.vistacontrol');
    })->name('control.panel');
    Route::get('user/panel', [UserController::class, 'userPanel'])->name('user.panel');
    Route::put('elementos/{id}', [ElementoController::class, 'update'])->name('elementos.update');

    // Ruta para mostrar el perfil
    Route::get('/editProfile', [AuthController::class, 'showEditProfile'])->name('editProfile');
    Route::get('/usuario/{id}', [UserController::class, 'show'])->name('user.show');
    Route::get('/usuario/panel', [UserController::class, 'mostrarPerfil']);
    Route::get('/perfil', [UserController::class, 'mostrarPerfil'])->name('perfil');
});
