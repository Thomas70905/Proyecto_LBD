<?php

use Illuminate\Support\Facades\Route;
use App\Models\Cliente;

Route::get('/', function () {
    // Fetch all clientes
    $clientes = Cliente::all();

    // Return the welcome view with clientes data
    return view('welcome', compact('clientes'));
});