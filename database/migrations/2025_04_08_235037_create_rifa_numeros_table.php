<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRifaNumerosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rifa_numeros', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('numero')->unique();
            $table->string('nome')->nullable();
            $table->string('telefone')->nullable();
            $table->string('comprovante')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('vendedor_id')->nullable();
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
        Schema::dropIfExists('rifa_numeros');
    }
}
