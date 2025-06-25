<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HolaMundoController extends Controller

{
    public function mostrar_hola_mundo()
{
 return view('vista_hola_mundo');
}
}
