<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dueno extends Model
{
    use HasFactory;
    protected $table='duenos';
    protected $primary_key='id';

    protected $fillable = [
        'nombre_dueno',
        'usuario_fk',
        'columna_1',
        'columna_2',
        'columna_3',
        'columna_4',
    ];
}
