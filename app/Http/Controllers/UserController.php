<?php

namespace App\Http\Controllers;

use App\Models\Proprietario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Funcao para trazer todos proprietarios.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexProprietario()
    {
        $utilizador = User::all();
        $utilizador = Proprietario::all();
        return view('listProprietarios', ['user' => $utilizador]);
    }

    /**
     * Funcao para carregar formulario de cadastro do proprietario.
     *
     * @return \Illuminate\Http\Response
     */
    public function createProprietario()
    {
        return view('/');
    }

    /**
     * Funcao para salvar dados do proprietario na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function storeProprietario(Request $request){
        $user = new User();
        $user->user_tipo = 'proprietario';
        $user->name = $request->input('nome_pro');
        $user->email = $request->input('email');
        $user->username = $request->email;
        $user->password = Hash ::make($request->input('password'));
        $email = User::all()->where('email', '=', $user->email)->count();
        if($email > 0){
            $addProprietario['success'] = false;
            $addProprietario['mensagem'] = 'Esse email ja esta registado no sistema!';
            return response()->json($addProprietario);
        }
        $user->save();
        $id_user = $user->id;
        
        $utilizador = new Proprietario();
        $utilizador->id_user = $id_user;
        //-------------------------upload de documento de identificacao--------------------------------
       /* if($request->hashFile('doc_identificacao') && $request->file('doc_identificacao')->isValid()){
            $requestDocument = $request->doc_identificacao;
            $extension = $requestDocument->extension();
            $docName = md5($requestDocument->doc_identify->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $request->doc_identificacao->move(public_path(), $docName);
            $utilizador->doc_identificacao = $docName;
        }*/
        $utilizador->doc_identificacao = $request->input('doc_identificacao');
        $utilizador->data_nascimento = $request->input('data_nascimento');
        $utilizador->telefone = $request->input('telefone');
        $utilizador->endereco = $request->input('endereco');
        
        if($utilizador->save()){
            return redirect()->route('/')->with(['Mensagem' => 'Proprietario Cadastrado com sucesso'], Response::HTTP_OK);
        }else{
            return redirect('/')->with(['Mensagem' => 'Erro no cadastro'], Response::HTTP_INTERNAL_SERVER_ERROR);
        } 
    }

    /**
     * Funcao para visualizar dados de um Proprietario.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showProprietario($id)
    {
        $utilizador = User::findOrFail($id);
        return view('/', ['user' => $utilizador]);
    }

    /**
     * Funcao para trazer formulario para editar dados do proprietario.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editProprietario($id)
    {
        $utilizador = User::findOrFail($id);
        $utilizador = Proprietario::findOrFail($id);
        return view('editproprietario', ['user' => $utilizador]);
    }



    /**
     * Funcao para actualizar dados do proprietario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProprietario(Request $request)
    {
        $data = $request->all();
        // ---------------------upload de documento de identificacao------------------------------ 
        if($request->hashFile('doc_identify') && $request->file('doc_identify')->isValid()){
            $requestDocument = $request->doc_identify;
            $extension = $requestDocument->extension();
            $docName = md5($requestDocument->doc_identify->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $request->doc_identify->move(public_path(), $docName);
            $data['doc_identify'] = $docName;
        }
        if($request->all()){
            return redirect()->route('/')->with(['Mensagem' => 'Seus dados foram actualizados com sucesso'], Response::HTTP_OK);
        }else{
            return redirect('/')->with(['Mensagem' => 'Erro ao actualizar os dados'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        
    }


    /**
     * Funcao para eliminar um proprietario.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyProprietario($id)
    {
        User::findOrFail($id)->delete();
        Proprietario::findOrFail($id)->delete();

        return redirect()->route('/')->with(['Mensagem' => 'Proprietario eliminado com sucesso'], Response::HTTP_OK);
    }
}
