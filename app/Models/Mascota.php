<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mascota extends Model
{
    protected $table = 'mascotas';
    protected $primaryKey = 'mascotaid';
    public $timestamps = false;

    protected $fillable = [
        'clienteid',
        'nombre',
        'raza',
        'edad',
        'peso',
        'color',
        'fecha_nacimiento'
    ];

    protected $dates = [
        'fecha_nacimiento'
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'clienteid');
    }

    public function historial(): HasMany
    {
        return $this->hasMany(Historial::class, 'mascotaid');
    }

    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class, 'mascotaid');
    }

    public function getEdadCalculada()
    {
        return \DB::selectOne('SELECT fn_calcular_edad_mascota(?) as edad', [$this->fecha_nacimiento])->edad;
    }
}