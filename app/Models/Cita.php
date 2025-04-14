<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    // Table name is "citas"
    protected $fillable = [
        'idMascota',
        'fechaInicio',
        'idServicio',
        'descripcion',
        'asistencia',
    ];

    // Relationships:
    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'idMascota');
    }
    
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'idServicio');
    }
}