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
        Schema::create('anuncio_like', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_anuncio');
            $table->unsignedBigInteger('id_like');
            $table->timestamps();
        });

        Schema::table('anuncio_like', function(Blueprint $table){

            /**
             * Aqui estamos alterando a tabela e 
             * Colocando uma chave estrangeira no 
             * campo que armazena o ID do anuncio
             */
            $table->foreign('id_anuncio')->references('id')->on('anuncios')->onDelete('cascade');
            
            /**
             * Aqui estamos alterando a tabela e 
             * Colocando uma chave estrangeira no 
             * campo que armazena o ID de like
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
        Schema::dropIfExists('anuncio_like');
    }
};
