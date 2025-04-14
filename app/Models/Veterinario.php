<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Veterinario extends Model
{
    // Table name is "veterinarios"
    protected $fillable = [
        'nombreCompleto',
        'fechaInicio',
        'telefono',
        'especialidad',
        'idUsuario',
    ];

    // Relationship: A veterinario belongs to a usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario');
    }
}