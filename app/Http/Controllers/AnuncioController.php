<?php

namespace App\Http\Controllers;

use App\Models\Anuncio;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AnuncioController extends Controller
{
    /**
     * Funcao para trazer todos anuncios publicados.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $anuncio = Anuncio::all();
        return view('', ['anuncio' => $anuncio]);
    }

    /**
     * Funcao para trazer formulario de publicacao de um anuncio.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('newanuncio');
    }

     /**
     * Funcao para pesquisa de anuncios.
     *
     * @return \Illuminate\Http\Response
     */
    public function pesquisa()
    {
        //
        $search = request('search');
        if($search){
            $anuncio = Anuncio::where([
                ['titulo', 'like', '%', $search. '%']
            ])->get();
        }
        return view('', ['anuncio' => $anuncio, 'search' => $search]);
    }

    /**
     * Funcao para salavar dados de um anuncio.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $anuncio = new Anuncio();
        $anuncio->tipo_conta = $request->input('tipo_conta');
        $anuncio->tipo_servico = $request->input('tipo_servico');
        $anuncio->tipo_arrenda = $request->input('tipo_arrenda');
        $anuncio->tipo_imovel = $request->input('tipo_imovel');
        $anuncio->infraestrutura = $request->input('infraestrutura');
        $anuncio->endereco = $request->input('endereco');
        $anuncio->paragem = $request->input('paragem');
        $anuncio->dis_paragem = $request->input('dis_paragem');
        $anuncio->meio_locomocao = $request->input('meio_locomocao');
        $anuncio->num_cadastro = $request->input('num_cadastro');
        $anuncio->tipo_infra = $request->input('tipo_infra');
        $anuncio->num_quartos = $request->input('num_quartos');
        $anuncio->area_total = $request->input('area_total');
        $anuncio->num_andar = $request->input('num_andar');
        $anuncio->reparacoes = $request->input('reparacoes');
        $anuncio->varanda = $request->input('varanda');
        $anuncio->vista = $request->input('vista');
        $anuncio->estilo_cozinha = $request->input('estilo_cozinha');
        $anuncio->planificacao = $request->input('planificacao');
        $anuncio->nome_infra = $request->input('nome_infra');
        $anuncio->data_contrucao = $request->input('data_construcao');
        $anuncio->elevador = $request->input('elevador');
        $anuncio->elevador_carga = $request->input('elevador_carga');
        $anuncio->rampa = $request->input('rampa');
        $anuncio->colector_lixo = $request->input('colector_lixo');
        $anuncio->seguranca = $request->input('seguranca');
        $anuncio->parqueamento = $request->input('parqueamento');
        $anuncio->garagemn = $request->input('garagem');
        
        //-----------------------upload imagem -------------------------------------------
        if($request->hasFile('imagem') && $request->file('imagem')->isValid()){

            $requestImagem =$request->imagem;
            $extension = $requestImagem->extension();
            $imagemName=md5($requestImagem->imagem->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $request->imagem->move(public_path('ficheiros/anuncio/imagem'), $imagemName);
            $anuncio->imagem = $imagemName;
        }

        $anuncio->video = $request->input('video');
        $anuncio->titulo = $request->input('titulo');
        $anuncio->descricao = $request->input('descricao');
        $anuncio->preco_mensal = $request->input('preco_mensal');
        $anuncio->p_negociavel = $request->input('p_negociavel');
        $anuncio->p_extenso = $request->input('p_extenso');
        $anuncio->taxa_mensal = $request->input('taxa_mensal');
        $anuncio->pre_pagamento = $request->input('pre_pagamento');
        $anuncio->porcentagem_cliente = $request->input('porcentagem_cliente');
        $anuncio->porcentagem_agente = $request->input('porcentagem_agente');
        $anuncio->telefone1 = $request->input('telefone1');
        $anuncio->telefone2 = $request->input('telefone2');
        $anuncio->whatsap = $request->input('whatsap');

        $user = auth()->user();
        $anuncio->id_user = $user->id;

        if($anuncio->save()){
            return redirect('/')->with(['Mensagem' => 'Anuncio publicado com sucesso'], Response::HTTP_OK);
        }else{
            return redirect('/')->with(['Mensagem' => 'Erro ao publicar anuncio'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Funcao para trazer dados de um anuncio.
     *
     * @param  \App\Models\Anuncio  $anuncio
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $anuncio = Anuncio::findOrFail($id);
        return view('', ['anuncio' => $anuncio]);

    }

    /**
     * Funcao para carregar o formulario para editar dados de um anuncio.
     *
     * @param  \App\Models\Anuncio  $anuncio
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $anuncio = Anuncio::findOrFail($id);
        return view('', ['anuncio' => $anuncio]);
    }

    /**
     * Funcao para actualizar dados de um anuncio.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Anuncio  $anuncio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $data = $request->all();

        //upload de imagem 
        if($request->hasFile('imagem') && $request->file('imagem')->isValid()){

            $requestImagem =$request->imagem;
            $extension = $requestImagem->extension();
            $imagemName=md5($requestImagem->imagem->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $request->imagem->move(public_path('ficheiros/anuncio/imagem'), $imagemName);
            $data['imagem'] = $imagemName;
        }

        Anuncio::findOrFail($request->id)->update($data);
        if($request->all()){
            return redirect()->route('')->with(['Mensagem' => 'Anuncio actualizado com sucesso']);
        }else{
            return redirect('/')->with(['Mensagem' => 'Erro ao actualizar dados do anuncio']);
        }
    }

    /**
     * Funcao para eliminar um anuncio.
     *
     * @param  \App\Models\Anuncio  $anuncio
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        Anuncio::findOrFail($id)->delete();
        return redirect()->route('')->with(['Mensagem' => 'Anuncio eliminado com sucesso']);
    }
}
