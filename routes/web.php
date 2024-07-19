<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
Use App\Http\Controllers\WelcomeController;

Route::get('/', [WelcomeController::class, 'index']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/create', [AuthController::class, 'createLogin'])->name('register');




