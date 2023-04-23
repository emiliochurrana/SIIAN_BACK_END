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
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_empresa');
            $table->string('doc_indentificacao');
            $table->string('data_nascimento');
            $table->json('telefone')->nullable();
            $table->string('endereco');
            $table->string('curriculum');
            $table->timestamps();
        });

        Schema::table('funcionarios', function (Blueprint $table){
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_empresa')->references('id')->on('users')->onDelete('cascade');
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
