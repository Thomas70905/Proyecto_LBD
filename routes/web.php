<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetsController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SignInController;
use App\Http\Controllers\PasswordRecoveryController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('password/recovery', [PasswordRecoveryController::class, 'show'])
    ->name('password.request');

Route::post('password/email', [PasswordRecoveryController::class, 'sendResetLink'])
    ->name('password.email');

Route::get('password/change', [PasswordRecoveryController::class, 'showChangePassword'])
    ->name('password.change');

Route::post('password/update', [PasswordRecoveryController::class, 'updatePassword'])
    ->name('password.update');

Route::get('/login', [LoginController::class, 'show'])->name('login.show');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/signin', [SignInController::class, 'show'])->name('signin.form');
Route::post('/signin', [SignInController::class, 'register'])->name('signin.register');

Route::resource('pets', PetsController::class);
Route::resource('employees', EmployeeController::class);
Route::resource('inventory', InventoryController::class);
Route::resource('services', ServiceController::class);
Route::resource('appointments', AppointmentController::class);
