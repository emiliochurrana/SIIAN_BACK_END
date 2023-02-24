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
        Schema::create('corrector', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            //$table->string('nome');
            $table->string('doc_verificacao');
            $table->string('especializacao');
            $table->string('ano_experiencia');
            $table->string('endereco');
            $table->text('sobre');
            $table->integer('id_anuncio');
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
        Schema::dropIfExists('corrector');
    }
};
