<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompraProducto extends Model
{
    protected $table = 'tbl_compra_producto';

    protected $fillable = [
        'id_compra', 'id_producto', 'cantidad', 'total', 'estado'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}
