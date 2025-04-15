<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';

    protected $fillable = [
        'email',
        'password',
        'role',
    ];

    // Puedes agregar mutators para hashear la contraseña, relaciones, etc.
}