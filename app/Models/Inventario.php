<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    // Table name is "inventario"
    protected $fillable = [
        'nombreProducto',
        'descripcion',
        'precio',
        'cantidadUnidades',
        'fechaCaducidad',
    ];
}