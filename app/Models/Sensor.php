<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;
    protected $table='sensores';
    protected $primary_key='id';

    protected $fillable = [
        'nombre_sensor',
        'columna_1',
        'columna_2',
        'columna_3',
        'columna_4',
    ];
}
