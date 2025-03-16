<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cita extends Model
{
    protected $table = 'citas';
    protected $primaryKey = 'citaid';
    public $timestamps = false;

    protected $fillable = [
        'mascotaid',
        'servicioid',
        'fecha_hora',
        'estado'
    ];

    protected $dates = [
        'fecha_hora'
    ];

    public function mascota(): BelongsTo
    {
        return $this->belongsTo(Mascota::class, 'mascotaid');
    }

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class, 'servicioid');
    }

    public static function agendarCita($mascotaId, $servicioId, $fechaHora)
    {
        \DB::statement(
            'BEGIN sp_agendar_cita(:mascota_id, :servicio_id, :fecha_hora); END;',
            [
                'mascota_id' => $mascotaId,
                'servicio_id' => $servicioId,
                'fecha_hora' => $fechaHora
            ]
        );
    }

    public function actualizarEstado($estado)
    {
        \DB::statement(
            'BEGIN sp_actualizar_estado_cita(:cita_id, :estado); END;',
            [
                'cita_id' => $this->citaid,
                'estado' => $estado
            ]
        );
    }
}