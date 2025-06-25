<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'tbl_categorias';

    protected $fillable = [
        'descripcion',
        'estado'
    ];

    public $timestamps = true;
}
