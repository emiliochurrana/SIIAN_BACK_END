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
        Schema::create('user_corrector', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_correctora');
            $table->timestamps();
        });

        Schema::table('user_corrector', function(Blueprint $table){

            /**
             * Aqui estamos alterando a tabela e 
             * Colocando uma chave estrangeira no 
             * campo que armazena o ID do Usuario
             */
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            
            /**
             * Aqui estamos alterando a tabela e 
             * Colocando uma chave estrangeira no 
             * campo que armazena o ID da corretor
             */
            $table->foreign('id_corretora')->references('id')->on('correctoras')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_corrector');
    }
};
