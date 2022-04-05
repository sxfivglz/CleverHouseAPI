<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleHabitacion extends Model
{
    use HasFactory;
    protected $table='detalle_habitaciones';
    protected $primary_key='id';

    protected $fillable = [
        'habitacion_fk',
        'detalle_fk',
        'columna_1',
        'columna_2',
        'columna_3',
        'columna_4',
    ];
}
