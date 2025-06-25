<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){

        $usuarios = User::select('id', 'name', 'email')
        ->orderBy('id', 'asc')
        ->get();

        return view('usuarios.index', compact('usuarios'));

    }
}
