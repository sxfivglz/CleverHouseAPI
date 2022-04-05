<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Casa extends Model
{
    use HasFactory;
    protected $table='casas';
    protected $primary_key='id';

    protected $fillable = [
        'nombre_casa',
        'direccion',
        'columna_1',
        'columna_2',
        'columna_3',
        'columna_4',
    ];
}
