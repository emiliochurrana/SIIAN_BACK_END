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
        Schema::create('publicidades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->string('tipo_publicidade');
            $table->string('titulo');
            $table->string('imagem');
            $table->text('descricao');
            $table->string('endereco');
            $table->integer('tempo_pago');
            $table->float('total_pago');
            $table->timestamps();
        });

        Schema::table('publicidades', function (Blueprint $table){
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publicidades');
    }
};
