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
        Schema::create('construtoras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->string('num_alvara');
            $table->string('num_nuit');
            $table->string('doc_alvara');
            $table->string('doc_nuit');
            $table->json('telefone')->nullable();
            $table->string('endereco');
            $table->timestamps();
        });

        Schema::table('construtoras', function (Blueprint $table){

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
        Schema::dropIfExists('construtoras');
    }
};
