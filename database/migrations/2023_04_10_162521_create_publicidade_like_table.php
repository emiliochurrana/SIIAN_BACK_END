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
        Schema::create('publicidade_like', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_publicidade');
            $table->unsignedBigInteger('id_like');
            $table->timestamps();
        });

        Schema::table('publicidade_like', function(Blueprint $table){

            /**
             * Aqui estamos alterando a tabela e 
             * Colocando uma chave estrangeira no 
             * campo que armazena o ID da publicidade
             */
            $table->foreign('_publicidade')->references('id')->on('publicidades')->onDelete('cascade');
            
            /**
             * Aqui estamos alterando a tabela e 
             * Colocando uma chave estrangeira no 
             * campo que armazena o ID de likes
             */
            $table->foreign('id_like')->references('id')->on('likes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publicidade_like');
    }
};
