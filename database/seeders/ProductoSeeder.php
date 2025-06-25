<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        Producto::insert([
            [
                'nombre' => 'Smatphone J1',
                'categoria_id' => 1,
                'codigo_barras' => '03254878996',
                'precio_venta' => '1200.00',
                'cantidad_stock' => 15,
                'estado' => 'true',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'laptop hp l2388',
                'categoria_id' => 2,
                'codigo_barras' => '03356778945',
                'precio_venta' => '2500.00',
                'cantidad_stock' => 5,
                'estado' => 'true',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'camiseta stronger',
                'categoria_id' => 3,
                'codigo_barras' => '03398578923',
                'precio_venta' => '190.00',
                'cantidad_stock' => 50,
                'estado' => 'true',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
