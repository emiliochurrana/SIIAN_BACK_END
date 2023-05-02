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
        //
        $userauth = auth()->user();
        $publicidades = $userauth->publicidadeUser;
        return view('', ['publicidades' => $publicidades]);
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
        $publicidade = new Publicidade();
        $user = auth()->user();
        $publicidade->id_user = $user->id;
        $publicidade->tipo_publicidade = $request->input('tipo_publicidade');
        $publicidade->titulo = $request->input('titulo');
        
          //-----------------------upload imagem -------------------------------------------
          if($request->hasFile('imagem') && $request->file('imagem')->isValid()){

            $requestImagem =$request->imagem;
            $extension = $requestImagem->extension();
            $imagemName=md5($requestImagem->imagem->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $request->imagem->move(public_path('ficheiros/publicidades/imagem'), $imagemName);
            $publicidade->imagem = $imagemName;
        }

        $publicidade->descricao = $request->input('descricao');
        $publicidade->endereco = $request->input('endereco');
        $publicidade->tempo_pago = $request->input('tempo_pago');
        $publicidade->total_pago = $request->input('total_pago');

        if($publicidade->save()){

            return redirect()->route('/')->with('msg', 'Publicidade fixada com sucesso!');
        }else{
            return redirect()->route('/')->with('msg', 'Erro ao fixar a publicidade');
        }
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
    }
}
