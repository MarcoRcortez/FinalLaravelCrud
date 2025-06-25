<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        // Esto traerá todas las categorías (activas e inactivas)
        $categorias = Categoria::all();
        return view('categorias.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:45'
        ]);

        Categoria::create([
            'descripcion' => $request->descripcion,
            'estado' => true
        ]);

        return redirect()->route('categorias.index')->with('success', 'Categoría creada correctamente');
    }

    public function show(Categoria $categoria)
    {
        return response()->json($categoria);
    }

    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'descripcion' => 'required|string|max:45'
        ]);

        $categoria->update([
            'descripcion' => $request->descripcion
        ]);

        return response()->json(['message' => 'Categoría actualizada']);
    }

    public function destroy($id)
    {
        $categoria = Categoria::find($id);
        if ($categoria) {
            // CAMBIO AQUÍ: En lugar de cambiar el estado, eliminamos el registro.
            $categoria->delete(); // Esto elimina físicamente la categoría de la base de datos
            return response()->json(['success' => true, 'message' => 'Categoría eliminada correctamente.']); // Mensaje más específico
        }
        return response()->json(['success' => false, 'message' => 'Categoría no encontrada.'], 404); // Mensaje más específico
    }
}
