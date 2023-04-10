<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_construtora', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_construtora');
            $table->timestamps();
        });

        Schema::table('user_construtora', function(Blueprint $table){

            /**
             * Aqui estamos alterando a tabela e 
             * Colocando uma chave estrangeira no 
             * campo que armazena o ID do Usuario
             */
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            
            /**
             * Aqui estamos alterando a tabela e 
             * Colocando uma chave estrangeira no 
             * campo que armazena o ID da construtoras
             */
            $table->foreign('id_construtora')->references('id')->on('construtoras')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_construtora');
    }
};
