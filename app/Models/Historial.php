<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Historial extends Model
{
    protected $table = 'historial';
    protected $primaryKey = 'historialid';
    public $timestamps = false;

    protected $fillable = [
        'mascotaid',
        'fecha_consulta',
        'diagnostico',
        'tratamiento',
        'observaciones'
    ];

    protected $dates = [
        'fecha_consulta'
    ];

    public function mascota(): BelongsTo
    {
        return $this->belongsTo(Mascota::class, 'mascotaid');
    }

    public static function historialCompleto()
    {
        return DB::select('SELECT * FROM vw_historial_mascotas');
    }
}