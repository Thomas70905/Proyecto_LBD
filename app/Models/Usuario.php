<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    // Table name is "usuarios" (default for model "Usuario")
    protected $fillable = [
        'email',
        'password',
        'role',
    ];

    // You may use mutators, relationships, etc.
}