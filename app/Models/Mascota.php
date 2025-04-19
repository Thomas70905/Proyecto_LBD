<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mascota extends Model
{
    // Table name is "mascotas"
    protected $fillable = [
        'nombre_completo',
        'edad',
        'peso',
        'raza',
        'especie',
        'idCliente',
    ];

    // Relationship: A mascota belongs to a cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente');
    }
}