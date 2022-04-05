<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle extends Model
{
    use HasFactory;
    protected $table='detalles';
    protected $primary_key='id';

    protected $fillable = [
        'casa_fk',
        'dueno_fk',
        'invitado_fk',
        'columna_1',
        'columna_2',
        'columna_3',
        'columna_4',
    ];
}
