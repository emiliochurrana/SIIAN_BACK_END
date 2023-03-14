<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexProprietario(User $utilizador)
    {
        //
        return view('listProprietarios', ['user' => $utilizador]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }

    //-------------------------------------Cadastro de Propietarios------------------------------
     public function storeProprietario(Request $request)
    {  
        //
        $utilizador = new User();
        $utilizador->nome_pro = $request->input('nome_pro');
        $utilizador->email = $request->input('email');
        //upload de documento de identificacao 
        if($request->hashFile('doc_identify') && $request->file('doc_identify')->isValid()){
            $requestDocument = $request->doc_identify;
            $extension = $requestDocument->extension();
            $docName = md5($requestDocument->doc_identify->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $request->doc_identify->move(public_path(), $docName);
            $utilizador->doc_identify = $docName;
        }

        $utilizador->data_nasc = $request->input('data_nasc');
        $utilizador->telefone = $request->input('telefone');
        $utilizador->endereco = $request->input('endereco');
        $utilizador->password = Hash ::make($request->input('password'));

        if($utilizador->save()){
            return response()->json(['Mensagem' => 'Proprietario Cadastrado com sucesso com suscesso'], Response::HTTP_OK);
        }else{
            return response()->json(['Mensagem' => 'Erro no cadastro'], Response::HTTP_INTERNAL_SERVER_ERROR);
        } 
        return redirect('/');
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     //---------------------------------Editar dados do proprietario----------------------------------
    public function formEditProprietario(User $utilizador){
        return view('nome_view', ['user' => $utilizador]);
    }
     public function editProprietario(User $utilizador, Request $request){

        $utilizador->tipo_user = 'proprietario';
        $utilizador->nome_pro = $request->input('nome_pro');

        if(filter_var($request->email, FILTER_VALIDATE_EMAIL)){
            $utilizador->email = $request->input('email');
        }

                    // ---------------------upload de documento de identificacao------------------------------ 
                if($request->hashFile('doc_identify') && $request->file('doc_identify')->isValid()){
                    $requestDocument = $request->doc_identify;
                    $extension = $requestDocument->extension();
                    $docName = md5($requestDocument->doc_identify->getClientOriginalName() . strtotime("now")) . "." . $extension;
                    $request->doc_identify->move(public_path(), $docName);
                    $utilizador->doc_identify = $docName;
                }

        $utilizador->data_nasc = $request->input('data_nasc');
        $utilizador->telefone = $request->input('telefone');
        $utilizador->endereco = $request->input('endereco');

        if(empty($request->password)){
            $utilizador->password = Hash ::make($request->input('password'));
        }
        

        if($utilizador->save()){
            return response()->json(['Mensagem' => 'Dados do proprietario actualizados com suscesso'], Response::HTTP_OK);
        }else{
            return response()->json(['Mensagem' => 'Erro ao actualizar os dados'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return redirect('/');

     }
    public function edit($id)
    {
        //
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

    //----------------------------------Eliminando o proprietario ------------------------------------
    public function destroyProprietario(User $utilizador){
        if($utilizador->delete()){
            return response()->json(['Mensagem' => 'Proprietario eliminado com sucesso'], Response::HTTP_OK);
        }else{
            return response()->json(['Mensagem' => 'Erro ao deletar proprietario'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return redirect()->route('');
    }
}
