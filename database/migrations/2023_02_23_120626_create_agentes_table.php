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
        Schema::create('agentes', function (Blueprint $table) {
            $table->id();
             //$table->foreignId('id_user')->constrained('users');
            //$table->string('nome_construtora');
            $table->string('num_alvara');
            $table->string('num_nuit');
            $table->string('doc_alvara');
            $table->string('doc_nuit');
            //$table->text('especializacao');
            //$table->json('telefone')->nullable();
            $table->string('endereco');
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
        Schema::dropIfExists('agentes');
    }
};
