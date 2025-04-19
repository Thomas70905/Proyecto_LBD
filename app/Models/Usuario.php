<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{

    protected $table = 'usuarios';

    protected $fillable = [
        'email',
        'password',
        'rol',
        'nombre_completo',
        'telefono',
        'en_recuperacion',
    ];
}