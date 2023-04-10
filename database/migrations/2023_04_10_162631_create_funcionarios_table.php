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
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->id();
            $table->string('doc_indentificacao');
            $table->string('data_nascimento');
            $table->json('telefone')->nullable();
            $table->string('endereco');
            $table->string('curriculum');
            $table->foreignId('id_corretora')->constrained('correctoras');
            $table->foreignId('id_agencia')->constrained('agencias');
            $table->foreignId('id_construtora')->constrained('construtoras');
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
        Schema::dropIfExists('funcionarios');
    }
};
