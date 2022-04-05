<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleSensoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_sensores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sensor_fk');
            $table->foreign('sensor_fk')->references('id')->on('sensores');
            $table->unsignedBigInteger('detalle_habitacion_fk');
            $table->foreign('detalle_habitacion_fk')->references('id')->on('detalle_habitaciones');
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
        Schema::dropIfExists('detalle_sensores');
    }
}
