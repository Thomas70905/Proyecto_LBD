<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SignInController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'show'])->name('login.show');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/signin', [SignInController::class, 'show'])->name('signin.form');
Route::post('/signin', [SignInController::class, 'register'])->name('signin.register');

Route::resource('employees', EmployeeController::class);
Route::resource('inventory', InventoryController::class);
Route::resource('services', ServiceController::class);
Route::resource('appointments', AppointmentController::class);
