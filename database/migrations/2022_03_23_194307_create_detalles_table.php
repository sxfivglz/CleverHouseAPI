<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('casa_fk');
            $table->foreign('casa_fk')->references('id')->on('casas');
            $table->unsignedBigInteger('dueno_fk');
            $table->foreign('dueno_fk')->references('id')->on('duenos');
            $table->unsignedBigInteger('invitado_fk');
            $table->foreign('invitado_fk')->references('id')->on('duenos');
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
        Schema::dropIfExists('detalles');
    }
}
