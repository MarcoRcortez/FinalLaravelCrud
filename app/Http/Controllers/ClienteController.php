<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all();
        return view('clientes.index', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dni' => 'required|string|max:20',
            'nombre' => 'required|string|max:40',
            'apellidos' => 'required|string|max:100',
            'celular' => 'nullable|numeric',
            'direccion' => 'nullable|string|max:80',
            'correo_electronico' => 'nullable|email|max:70',
        ]);

        Cliente::create($request->all());

        return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');
    }

    public function show(Cliente $cliente)
    {
        return response()->json($cliente);
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'dni' => 'required|string|max:20',
            'nombre' => 'required|string|max:40',
            'apellidos' => 'required|string|max:100',
            'celular' => 'nullable|numeric',
            'direccion' => 'nullable|string|max:80',
            'correo_electronico' => 'nullable|email|max:70',
        ]);

        $cliente->update($request->all());

        return response()->json(['message' => 'Cliente actualizado correctamente']);
    }

    public function destroy($id)
    {
        $cliente = Cliente::find($id);
        if ($cliente) {
            $cliente->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }
}
