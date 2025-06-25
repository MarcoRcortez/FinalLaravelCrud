<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Añadimos esta importación para usar transacciones

class ProductoController extends Controller
{
    public function index()
    {
        try {
            // Recuperar todos los productos sin filtrar por estado, ya que la eliminación será física
            $productos = Producto::select(
                'id',
                'nombre',
                'codigo_barras',
                'precio_venta',
                'cantidad_stock',
                'categoria_id',
                'estado'
            )->get();

            // Para las categorías, si también quieres ver todas (incluyendo inactivas),
            // elimina el filtro 'where("estado", true)' aquí también si lo tienes.
            $categorias = Categoria::select('id', 'descripcion')->get();

            return view('productos.index', compact('productos', 'categorias'));
        } catch (\Exception $e) {
            Log::error('Error al cargar productos en ProductoController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'No se pudieron cargar los productos. Por favor, inténtalo de nuevo más tarde.');
        }
    }

    public function store(Request $request)
    {
        $request->merge([
            'estado' => true, // Asegúrate de que el estado inicial sea verdadero (activo)
            'id_usuario' => Auth::id(),
        ]);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required|exists:tbl_categorias,id',
            'codigo_barras' => 'nullable|string|max:255|unique:tbl_productos,codigo_barras',
            'precio_venta' => 'required|numeric|min:0',
            'cantidad_stock' => 'required|integer|min:0',
            'estado' => 'required|boolean',
            'id_usuario' => 'required|integer',
        ], [
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'categoria_id.required' => 'La categoría es obligatoria.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',
            'codigo_barras.unique' => 'El código de barras ya está registrado.',
            'precio_venta.required' => 'El precio de venta es obligatorio.',
            'precio_venta.numeric' => 'El precio de venta debe ser un número.',
            'precio_venta.min' => 'El precio de venta no puede ser negativo.',
            'cantidad_stock.required' => 'La cantidad en stock es obligatoria.',
            'cantidad_stock.integer' => 'La cantidad en stock debe ser un número entero.',
            'cantidad_stock.min' => 'La cantidad en stock no puede ser negativa.',
        ]);

        try {
            Producto::create([
                'nombre' => $request->nombre,
                'categoria_id' => $request->categoria_id,
                'codigo_barras' => $request->codigo_barras,
                'precio_venta' => $request->precio_venta,
                'cantidad_stock' => $request->cantidad_stock,
                'estado' => $request->estado,
                'id_usuario' => $request->id_usuario,
            ]);

            return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al crear producto: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al crear el producto. Inténtalo de nuevo.');
        }
    }

    public function show(Producto $producto)
    {
        try {
            $productoFiltrado = Producto::select(
                'id',
                'nombre',
                'codigo_barras',
                'precio_venta',
                'cantidad_stock',
                'categoria_id',
                'estado'
            )
                ->where('id', $producto->id)
                ->where('id_usuario', Auth::id()) // Mantener este filtro por seguridad de usuario
                ->first();

            if ($productoFiltrado) {
                return response()->json($productoFiltrado);
            } else {
                return response()->json(['message' => 'Producto no encontrado o no tienes permisos para verlo.'], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error en ProductoController@show para ID ' . $producto->id . ': ' . $e->getMessage());
            return response()->json(['message' => 'Error interno del servidor al cargar el producto.'], 500);
        }
    }

    public function update(Request $request, Producto $producto)
    {
        if ($producto->id_usuario !== Auth::id()) {
            return response()->json(['message' => 'No tienes permisos para editar este producto.'], 403);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required|exists:tbl_categorias,id',
            'codigo_barras' => 'nullable|string|max:255|unique:tbl_productos,codigo_barras,' . $producto->id,
            'precio_venta' => 'required|numeric|min:0',
            'cantidad_stock' => 'required|integer|min:0',
            'estado' => 'boolean', // 'boolean' valida que el valor sea booleano o convertible a booleano
        ], [
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'categoria_id.required' => 'La categoría es obligatoria.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',
            'codigo_barras.unique' => 'El código de barras ya está registrado por otro producto.',
            'precio_venta.required' => 'El precio de venta es obligatorio.',
            'precio_venta.numeric' => 'El precio de venta debe ser un número.',
            'precio_venta.min' => 'El precio de venta no puede ser negativo.',
            'cantidad_stock.required' => 'La cantidad en stock es obligatoria.',
            'cantidad_stock.integer' => 'La cantidad en stock debe ser un número entero.',
            'cantidad_stock.min' => 'La cantidad en stock no puede ser negativa.',
        ]);

        try {
            $producto->update([
                'nombre' => $request->nombre,
                'categoria_id' => $request->categoria_id,
                'codigo_barras' => $request->codigo_barras,
                'precio_venta' => $request->precio_venta,
                'cantidad_stock' => $request->cantidad_stock,
                // Si el 'estado' no se envía en el request (por ejemplo, en un formulario de edición),
                // mantenemos el estado actual del producto. Si se envía, lo actualizamos.
                'estado' => $request->has('estado') ? $request->estado : $producto->estado,
            ]);

            return response()->json(['message' => 'Producto actualizado exitosamente.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación al actualizar producto ' . $producto->id . ': ' . json_encode($e->errors()));
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error al actualizar producto ' . $producto->id . ': ' . $e->getMessage());
            return response()->json(['message' => 'Error interno del servidor al actualizar el producto.'], 500);
        }
    }

    public function destroy($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json(['success' => false, 'message' => 'Producto no encontrado.'], 404);
        }

        try {
            DB::transaction(function () use ($producto) {
                // PRIMERO: Eliminar todos los registros de CompraProducto que referencian este Producto
                // Esto es CRUCIAL para mantener la integridad referencial si no tienes onDelete('cascade') en tu BD.
                // Asegúrate de que el modelo Producto tiene una relación 'compras()' definida (hasMany a CompraProducto)
                if ($producto->compras) { // Verificar si la relación 'compras' existe y tiene elementos
                     $producto->compras()->delete();
                }

                // SEGUNDO: Eliminar físicamente el Producto
                $producto->delete();
            });

            return response()->json(['success' => true, 'message' => 'Producto eliminado físicamente.']);
        } catch (\Exception $e) {
            Log::error('Error al eliminar producto ' . $producto->id . ': ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error interno del servidor al eliminar el producto.'], 500);
        }
    }
}
