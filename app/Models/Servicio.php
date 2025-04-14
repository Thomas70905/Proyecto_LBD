<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    // Table name is "servicios"
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'duracion',
    ];
}