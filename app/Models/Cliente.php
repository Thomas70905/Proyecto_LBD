<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'clienteid';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellido',
        'telefono',
        'email',
        'direccion',
        'fecha_registro'
    ];

    protected $dates = [
        'fecha_registro'
    ];

    public function mascotas(): HasMany
    {
        return $this->hasMany(Mascota::class, 'clienteid');
    }

    public function getNumeroMascotas()
    {
        return \DB::selectOne('SELECT fn_obtener_num_mascotas(?) as num_mascotas', [$this->clienteid])->num_mascotas;
    }
}