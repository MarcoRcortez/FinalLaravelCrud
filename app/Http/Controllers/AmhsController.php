<?php

namespace App\Http\Controllers; // <<-- ¡ASEGÚRATE DE QUE ESTE NAMESPACE ES CORRECTO!

use Illuminate\Http\Request;

class AmhsController extends Controller // <<-- ¡ASEGÚRATE DE QUE EL NOMBRE DE LA CLASE ES EXACTO!
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Aquí devuelves la vista amhs.blade.php
        return view('amhs');
    }

    // ... otros métodos si los tienes
}
