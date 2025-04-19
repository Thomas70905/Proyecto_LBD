<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
    // Table name is "administradores" (default for model "Administrador")
    protected $fillable = [
        'idUsuario',
    ];

    // Relationship: A administrador belongs to a usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario');
    }
}