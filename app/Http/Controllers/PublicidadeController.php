<?php

namespace App\Http\Controllers;

use App\Models\Publicidade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PublicidadeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request('search');

        if ($search) {
            $userlog = auth()->user()->id;
            $publicidades = Publicidade::where([
                   ['id_user', $userlog], ['titulo', 'like', '%' .$search . '%'],
            ])->get();
        }else{
        $userauth = auth()->user();
        $publicidades = $userauth->publicidadeUser;
        }
        return view('', ['publicidades' => $publicidades, 'search' => $search]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $userauth = auth()->user()->user_tipo;
        if($userauth == 'construtora'){
            $publicidade = new Publicidade();
            $user = auth()->user();
            $publicidade->id_user = $user->id;
            $publicidade->tipo_publicidade = $request->input('tipo_publicidade');
            $publicidade->espaco = $request->input('espaco');
            
            //-----------------------upload logotipo -------------------------------------------
            if($request->hasFile('logotipo') && $request->file('logotipo')->isValid()){

                $requestImagem =$request->logotipo;
                $extension = $requestImagem->extension();
                $imagemName=md5($requestImagem->getClientOriginalName() . strtotime("now")) . "." . $extension;
                $requestImagem->move(public_path('ficheiros/publicidades/logotipos'), $imagemName);
                $publicidade->logotipo = $imagemName;
            }
            
            //-----------------------upload imagem -------------------------------------------
            if($request->hasFile('imagem') && $request->file('imagem')->isValid()){

                $requestImagem =$request->imagem;
                $extension = $requestImagem->extension();
                $imagemName=md5($requestImagem->imagem->getClientOriginalName() . strtotime("now")) . "." . $extension;
                $request->imagem->move(public_path('ficheiros/publicidades/imagens'), $imagemName);
                $publicidade->imagem = $imagemName;
            }

            $publicidade->imovel_servico = $request->input('imovel_servico');
            $publicidade->empreendimento = $request->input('empreendimento');
            $publicidade->descricao = $request->input('descricao');
            $publicidade->telefone = $request->input('telefone');
            $publicidade->link = $request->input('link');
            $publicidade->tipo_promocao = $request->input('tipo_promocao');
            $publicidade->promocao = $request->input('promocao');
            $publicidade->paragem = $request->input('paragem');
            $publicidade->tempo = $request->input('tempo');
            $publicidade->informacao_legal = $request->input('informacao_legal');
            $publicidade->instituicao = $request->input('instituicao');
            $publicidade->validade = $request->input('validade');
            $publicidade->limite_financeiro = $request->input('limite_financeiro');
            $publicidade->taxa_juro = $request->input('taxa_juro');
            $publicidade->primeira_prestacao = $request->input('primeira_prestacao');

            if($publicidade->save()){

                return redirect()->route('/')->with('msg', 'Publicidade fixada com sucesso!');
            }else{
                return redirect()->back()->with('msg', 'Erro ao fixar a publicidade');
            }
        }
    }

        /**
     * Função para dar um like em uma publicidade.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function likePublicidade($id){
        $user = auth()->user();
        $user->publicidadeLike()->attach($id);
    }

    /**
     * Função para dar um deslike em uma publicidade.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deslikePublicidade($id){
        $user = auth()->user();
        $user->publicidadeLike()->detach($id);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $user = User::findOrFail($id);
        $publicidades = $user->publicidadeUser->get();
        return view('', ['publicidades' => $publicidades]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = User::findOrFail($id);
        $publicidades = $user->publicidadeUser->get();
        return view('', ['publicidades' => $publicidades]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $data = $request->all();
            //-----------------------upload logotipo -------------------------------------------
            if($request->hasFile('logotipo') && $request->file('logotipo')->isValid()){

                $requestImagem =$request->logotipo;
                $extension = $requestImagem->extension();
                $imagemName=md5($requestImagem->getClientOriginalName() . strtotime("now")) . "." . $extension;
                $requestImagem->move(public_path('ficheiros/publicidades/logotipos'), $imagemName);
                $data['logotipo'] = $imagemName;
            }
           //-----------------------upload imagem -------------------------------------------
           if($request->hasFile('imagem') && $request->file('imagem')->isValid()){

            $requestImagem =$request->imagem;
            $extension = $requestImagem->extension();
            $imagemName=md5($requestImagem->imagem->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $request->imagem->move(public_path('ficheiros/publicidades/imagem'), $imagemName);
            $data['imagem'] = $imagemName;
        }
        $publicidade = Publicidade::findOrFail($request->id)->update($data);

        if($publicidade){
            return redirect()->route('')->with(['msgSucessUpdate' => 'Publicidade actualizada com sucesso']);
        }else{
            return redirect()->back()->with(['msgErrorUpdate' => 'Erro ao actualizar a publicidade']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        Publicidade::findOrFail($id)->delete();
        return redirect()->route('')->with(['msgDelete' => 'Publicidade eliminada com sucesso']);
    }
}
