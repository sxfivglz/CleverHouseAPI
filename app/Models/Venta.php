<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;
    protected $table='ventas';
    protected $primary_key='id';
    
    protected $fillable = [
        'fecha_venta',
        'cliente_id',
        'forma_pago',
        'nombre_vendedor',
        'total',
    ];
}
