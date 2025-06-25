<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\CompraProducto;
use App\Models\Cliente;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function index()
    {
        // MODIFICACIÓN AQUÍ: Ya no filtramos por estado 'A'
        // Esto listará todas las compras, incluyendo las que antes tenían estado 'I' o 'P',
        // ya que la acción de borrar ahora será física.
        $compras = Compra::with('cliente')->get();
        return view('compras.index', compact('compras'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $productos = Producto::all();
        return view('compras.create', compact('clientes', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:tbl_clientes,id',
            'fecha' => 'required|date',
            'medio_pago' => 'required|in:E,T,C',
            'comentario' => 'nullable|string|max:300',
            'productos' => 'required|array|min:1',
            'productos.*.id_producto' => 'required|exists:tbl_productos,id',
            'productos.*.cantidad' => 'required|integer|min:1|max:99999',
        ]);

        DB::transaction(function () use ($request) {
            $totalGeneralCalculado = 0;

            $productoIds = collect($request->productos)->pluck('id_producto')->unique()->toArray();
            $productosDB = Producto::whereIn('id', $productoIds)->get()->keyBy('id');

            $compra = Compra::create([
                'cliente_id' => $request->cliente_id,
                'fecha' => $request->fecha,
                'medio_pago' => $request->medio_pago,
                'comentario' => $request->comentario,
                'estado' => 'A', // El estado por defecto al crear seguirá siendo 'A'
            ]);

            foreach ($request->productos as $prod) {
                $idProducto = $prod['id_producto'];
                $cantidad = $prod['cantidad'];

                $producto = $productosDB->get($idProducto);

                if (!$producto) {
                    throw new \Exception("Producto con ID {$idProducto} no encontrado o inactivo.");
                }

                $precioUnitario = $producto->precio_venta;
                $totalProductoCalculado = $precioUnitario * $cantidad;

                CompraProducto::create([
                    'id_compra' => $compra->id,
                    'id_producto' => $idProducto,
                    'cantidad' => $cantidad,
                    'total' => $totalProductoCalculado,
                    'estado' => true
                ]);

                if ($producto->cantidad_stock >= $cantidad) {
                    $producto->cantidad_stock -= $cantidad;
                    $producto->save();
                } else {
                    throw new \Exception("Stock insuficiente para el producto: " . $producto->nombre);
                }

                $totalGeneralCalculado += $totalProductoCalculado;
            }
        });

        return redirect()->route('compras.index')->with('success', 'Compra registrada exitosamente');
    }

    public function show($id)
    {
        $compra = Compra::with('cliente', 'productos.producto')->findOrFail($id);
        return view('compras.show', compact('compra'));
    }

    public function destroy($id)
    {
        $compra = Compra::find($id);
        if ($compra) {
            DB::transaction(function () use ($compra) {
                // PRIMERO: Eliminar los registros relacionados en tbl_compra_producto
                // Esto es crucial para mantener la integridad referencial si no usas onDelete('cascade')
                $compra->productos()->delete(); // Utiliza la relación "productos" del modelo Compra

                // SEGUNDO: Eliminar físicamente la compra de tbl_compras
                $compra->delete();
            });

            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }
}
