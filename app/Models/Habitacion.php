<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
    use HasFactory;
    protected $table='habitaciones';
    protected $primary_key='id';

    protected $fillable = [
        'nombre_habitacion',
        'columna_1',
        'columna_2',
        'columna_3',
        'columna_4',
    ];
}
