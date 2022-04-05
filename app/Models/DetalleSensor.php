<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleSensor extends Model
{
    use HasFactory;
    protected $table='detalle_sensores';
    protected $primary_key='id';

    protected $fillable = [
        'sensor_fk',
        'detalle_habitacion_fk',
        'columna_1',
        'columna_2',
        'columna_3',
        'columna_4',
    ];
}
