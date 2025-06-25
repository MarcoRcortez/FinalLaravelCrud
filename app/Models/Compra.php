<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'tbl_compras';

    // Importante: 'total_compra' NO está en fillable porque no se asume su existencia sin migración.
    protected $fillable = [
        'cliente_id', 'fecha', 'medio_pago', 'comentario', 'estado'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function productos()
    {
        return $this->hasMany(CompraProducto::class, 'id_compra');
    }
}
