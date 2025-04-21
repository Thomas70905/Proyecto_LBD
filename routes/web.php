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
use App\Http\Controllers\AppointmentManagementController;
use App\Http\Controllers\AppointmentHistoryController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\ProductController;

/**
 * Archivo de rutas web
 * 
 * Este archivo define todas las rutas web de la aplicación.
 * Las rutas están organizadas por grupos según su funcionalidad
 * y nivel de acceso requerido.
 * 
 * Estructura:
 * 1. Rutas públicas
 * 2. Rutas de autenticación
 * 3. Rutas protegidas (requieren autenticación)
 *    - Panel de control
 *    - Gestión de mascotas
 *    - Gestión de citas
 *    - Gestión de servicios
 *    - Gestión de productos
 * 
 * Middleware:
 * - web: Procesamiento de sesiones y cookies
 * - auth: Verificación de autenticación
 * - verified: Verificación de correo electrónico
 * 
 * @package Routes
 */

// Rutas públicas
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
Route::resource('appointment-management', AppointmentManagementController::class);
Route::post('appointment-management/{id}/attendance', [AppointmentManagementController::class, 'updateAttendance'])->name('appointment-management.updateAttendance');
Route::post('appointment-management/{id}/products', [AppointmentManagementController::class, 'addProduct'])->name('appointment-management.addProduct');
Route::post('appointment-management/{id}/update-product-quantity', [AppointmentManagementController::class, 'updateProductQuantity'])->name('appointment-management.updateProductQuantity');
Route::post('appointment-management/{id}/remove-product', [AppointmentManagementController::class, 'removeProduct'])->name('appointment-management.removeProduct');

Route::get('/appointment-history', [AppointmentHistoryController::class, 'index'])->name('appointment-history.index');
Route::get('/appointment-history/{id}', [AppointmentHistoryController::class, 'show'])->name('appointment-history.show');
Route::get('/appointment-history/{id}/download', [AppointmentHistoryController::class, 'downloadPdf'])->name('appointment-history.download');

// Rutas protegidas
Route::middleware(['auth', 'verified'])->group(function () {
    // Panel de control
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    // Gestión de mascotas
    Route::resource('pets', PetController::class);
    
    // Gestión de citas
    Route::resource('appointments', AppointmentController::class);
    Route::get('appointments/history', [AppointmentHistoryController::class, 'index'])->name('appointments.history');
    Route::get('appointments/history/{appointment}', [AppointmentHistoryController::class, 'show'])->name('appointments.history.show');
    Route::get('appointments/history/{appointment}/pdf', [AppointmentHistoryController::class, 'downloadPdf'])->name('appointments.history.pdf');
    
    // Gestión de servicios (solo administradores)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('services', ServiceController::class);
        Route::resource('products', ProductController::class);
    });
});
