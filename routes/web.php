<?php
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;

Route::get('/', [WelcomeController::class, 'index']);
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::get('create', [AuthController::class, 'create'])->name('create');
Route::post('registrado', [AuthController::class, 'createpost'])->name('createpost');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('admin/panel', function () {
        return view('index.vistaadmin');
    })->name('admin.panel');

    Route::get('control/panel', function () {
        return view('index.vistacontrol');
    })->name('control.panel');

    Route::get('user/panel', function () {
        return view('index.vistausuario');
    })->name('user.panel');
});
