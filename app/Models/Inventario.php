<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Inventario extends Model
{
    protected $table = 'inventario';
    protected $primaryKey = 'productoid';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'cantidad',
        'nivel_critico',
        'fecha_caducidad',
        'precio'
    ];

    protected $dates = [
        'fecha_caducidad'
    ];

    public static function productosStockBajo()
    {
        return DB::select('SELECT * FROM vw_inventario_bajo');
    }

    public function registrarMovimiento($tipo, $cantidad)
    {
        DB::table('movimientos_inventario')->insert([
            'productoid' => $this->productoid,
            'tipo_movimiento' => $tipo,
            'cantidad' => $cantidad,
            'fecha_movimiento' => now()
        ]);
    }
}