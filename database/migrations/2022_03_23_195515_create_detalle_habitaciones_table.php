<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleHabitacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_habitaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('habitacion_fk');
            $table->foreign('habitacion_fk')->references('id')->on('habitaciones');
            $table->unsignedBigInteger('detalle_fk');
            $table->foreign('detalle_fk')->references('id')->on('detalles');
            $table->string('columna_1');
            $table->string('columna_2');
            $table->string('columna_3');
            $table->string('columna_4');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_habitaciones');
    }
}
