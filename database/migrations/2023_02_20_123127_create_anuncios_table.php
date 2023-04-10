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
        Schema::create('anuncios', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_conta');
            $table->string('tipo_servico');
            $table->string('tipo_arrenda');
            $table->string('tipo_imovel');
            $table->string('infraestrutura');
            $table->string('endereco');
            $table->string('paragem');
            $table->float('distancia_paragem');
            $table->string('meio_locomocao');
            $table->string('num_cadastro');
            $table->string('tipo_infraestrutura');
            $table->integer('num_quarto');
            $table->integer('area_total');
            $table->integer('num_andar');
            $table->string('reparacoes');
            $table->integer('varanda');
            $table->string('vista');
            $table->json('estilo_cozinha')->nullable();
            $table->string('planificacao');
            $table->string('nome_infraestrutura');
            $table->string('data_construcao');
            $table->integer('elevador');
            $table->integer('elevador_carga');
            $table->string('rampa');
            $table->string('coletor_lixo');
            $table->string('seguranca');
            $table->string('parqueamento');
            $table->string('garagem');
            $table->string('imagem');
            $table->string('video');
            $table->string('titulo_anuncio');
            $table->text('descricao');
            $table->float('preco_mensal');
            $table->float('preco_negociavel');
            $table->text('preco_mensal_extenso');
            $table->float('taxa_mensal');
            $table->float('pre_pagamento');
            $table->float('%_cliente');
            $table->float('%_agente');
            $table->json('telefone')->nullable();
            $table->integer('whatsapp');
            $table->foreignId('id_corretora')->constrained('correctoras');
            $table->foreignId('id_construtora')->constrained('construtoras');
            $table->foreignId('id_proprietario')->constrained('proprietarios');
            $table->foreignId('id_agencia')->constrained('agencias');
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
        Schema::dropIfExists('anuncios');
    }
};
