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
            $table->string('espaco');
            $table->string('imovel_servico')->nullable();
            $table->string('empreendimento')->nullable();
            $table->text('descricao')->nullable();
            $table->integer('telefone')->nullable();
            $table->string('link')->nullable();
            $table->string('tipo_promocao')->nullable();
            $table->string('promocao')->nullable();
            $table->string('paragem')->nullable();
            $table->string('tempo')->nullable();
            $table->text('informacao_legal')->nullable();
            $table->string('imagem')->nullable();
            $table->string('imagem_predefinida')->nullable();
            $table->string('instituicao')->nullable();
            $table->string('validade')->nullable();
            $table->string('limite_finaciamento')->nullable();
            $table->string('taxa_juro')->nullable();
            $table->string('primeira_prestacao')->nullable();
            $table->string('logotipo')->nullable();
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
