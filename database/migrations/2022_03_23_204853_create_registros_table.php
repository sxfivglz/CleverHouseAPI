<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registros', function (Blueprint $table) {
            $table->id();
            $table->string('medicion');
            $table->unsignedBigInteger('detalle_sensor_fk');
            $table->foreign('detalle_sensor_fk')->references('id')->on('detalle_sensores');
            $table->string('columna_1')->nullable();
            $table->string('columna_2')->nullable();
            $table->string('columna_3')->nullable();
            $table->string('columna_4')->nullable();
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
        Schema::dropIfExists('registros');
    }
}
