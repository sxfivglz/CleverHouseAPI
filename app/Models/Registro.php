<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    use HasFactory;
    protected $table='registros';
    protected $primary_key='id';

    protected $fillable = [
        'medicion',
        'detalle_sensor_fk',
        'columna_1',
        'columna_2',
        'columna_3',
        'columna_4',
    ];
}
