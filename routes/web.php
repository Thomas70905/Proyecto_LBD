<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AppointmentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/signin', function () {
    return view('signin');
});

Route::get('/password_recover', function () {
    return view('password_recover');
});

Route::resource('employees', EmployeeController::class);
Route::resource('inventory', InventoryController::class);
Route::resource('services', ServiceController::class);
Route::resource('appointments', AppointmentController::class);
