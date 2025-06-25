<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Categoria;
use App\Models\CompraProducto;

class Producto extends Model
{
    protected $table = 'tbl_productos';

    protected $fillable = [
        'nombre',
        'categoria_id',
        'codigo_barras',
        'precio_venta',
        'cantidad_stock',
        'id_usuario',
        'estado'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function compras()
    {
        return $this->hasMany(CompraProducto::class, 'id_producto');
    }
}
