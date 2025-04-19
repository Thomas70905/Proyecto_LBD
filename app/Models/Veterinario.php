<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Veterinario extends Model
{
    // Table name is "veterinarios"
    protected $fillable = [
        'idUsuario',
    ];

    // Relationship: A veterinario belongs to a usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario');
    }
}