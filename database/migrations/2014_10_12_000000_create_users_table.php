<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_usuario');
            $table->string('apellido_usuario');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('telefono');
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
        Schema::dropIfExists('users');
    }
}
