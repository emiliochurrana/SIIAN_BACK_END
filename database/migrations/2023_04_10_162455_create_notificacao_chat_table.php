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
        Schema::create('notificacao_chat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_notificacao');
            $table->unsignedBigInteger('id_chat');
            $table->timestamps();
        });

        Schema::table('notificacao_chat', function(Blueprint $table){

            /**
             * Aqui estamos alterando a tabela e 
             * Colocando uma chave estrangeira no 
             * campo que armazena o ID da notificacao
             */
            $table->foreign('id_notificacao')->references('id')->on('notificacoes')->onDelete('cascade');
            
            /**
             * Aqui estamos alterando a tabela e 
             * Colocando uma chave estrangeira no 
             * campo que armazena o ID da corretor
             */
            $table->foreign('id_chat')->references('id')->on('chats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notificacao_chat');
    }
};
