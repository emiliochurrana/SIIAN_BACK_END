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
            $table->unsignedBigInteger('id_user');
            $table->string('tipo_documento');
            $table->integer('data_nascimento');
            $table->string('numero_documento');
            $table->string('documento');
            $table->string('foto_doc');
            $table->json('telefone')->nullable();
            $table->string('endereco');
            $table->timestamps();
        });
        Schema::table('correctoras', function (Blueprint $table){

            $table->foreign('id_user')->reference('id')->on('users')->onDelete('cascade');

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
