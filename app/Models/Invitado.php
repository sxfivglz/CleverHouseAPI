<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitado extends Model
{
    use HasFactory;
    protected $table='invitados';
    protected $primary_key='id';

    protected $fillable = [
        'nombre_invitado',
        'usuario_fk',
        'columna_1',
        'columna_2',
        'columna_3',
        'columna_4',
    ];
}
