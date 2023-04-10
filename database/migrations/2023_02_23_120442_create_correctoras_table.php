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
        Schema::create('correctoras', function (Blueprint $table) {
            $table->id();
            //$table->foreignId('id_user')->constrained('users');
            //$table->string('nome_corretora');
            $table->string('tipo_documento');
            $table->integer('data_nascimento');
            $table->string('numero_documento');
            $table->string('documento');
            $table->string('foto_doc');
            //$table->text('especializacao');
            //$table->json('telefone')->nullable();
            //$table->string('endereco');
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
        Schema::dropIfExists('correctoras');
    }
};
