<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    // Table name is "clientes" (default for model "Cliente")
    protected $fillable = [
        'direccion',
        'idUsuario',
    ];

    // Relationship: A cliente belongs to a usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario');
    }
}