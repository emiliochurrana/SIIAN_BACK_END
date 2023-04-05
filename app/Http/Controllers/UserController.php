<?php

namespace App\Http\Controllers;

use App\Models\Agente;
use App\Models\Construtora;
use App\Models\Proprietario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

class UserController extends Controller
{

/**
 * ---------------------------------------------------------------------------------------
 * --------------------------------Proprietarios------------------------------------------
 * ---------------------------------------------------------------------------------------
 */
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
     * Funcao para pesquisa de Proprietarios.
     *
     * @return \Illuminate\Http\Response
     */
    public function pesquisaProprietarios()
    {
        //
        $search = request('search');
        if($search){
            $utilizador = Proprietario::where([
                ['nome', 'like', '%', $search. '%']
            ])->get();
        }
        return view('', ['user' => $utilizador, 'search' => $search]);
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
        $user->name = $request->input('nome');
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
            $docName = md5($requestDocument->doc_identificacao->getClientOriginalName() . strtotime("now")) . "." . $extension;
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
        $utilizador = Proprietario::findOrFail($id);
        return view('/', ['proprietario' => $utilizador]);
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
        if($request->hashFile('doc_identificacao') && $request->file('doc_identificacao')->isValid()){
            $requestDocument = $request->doc_identificacao;
            $extension = $requestDocument->extension();
            $docName = md5($requestDocument->doc_identificacao->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $request->doc_identificacao->move(public_path(), $docName);
            $data['doc_identificacao'] = $docName;
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

/**
 * ----------------------------------------------fim proprietarios --------------------------------------------------------
 */
     

/**
 * ------------------------------------------------------------------------------------------
 * ---------------------------------Construtoras---------------------------------------------
 * ------------------------------------------------------------------------------------------
 */
    /**
     * Funcao para trazer todas construtoras.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexConstrutora(){

        $utilizador = User::all();
        $utilizador = Construtora::all();
        return view('listConstruras', ['user' => $utilizador]);
    }

     /**
     * Funcao para carregar formulario de cadastro de uma construtora.
     *
     * @return \Illuminate\Http\Response
     */
    public function createConstrutora()
    {
        return view('/');
    }

    /**
     * Funcao para pesquisa de Construtoras.
     *
     * @return \Illuminate\Http\Response
     */
    public function pesquisaConstrutora()
    {
        //
        $search = request('search');
        if($search){
            $utilizador = Construtora::where([
                ['nome', 'like', '%', $search. '%']
            ])->get();
        }
        return view('', ['user' => $utilizador, 'search' => $search]);
    }

    /**
     * Funcao para salvar dados de uma Construtora na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeConstrutora(Request $request){
        $user = new User();
        $user->user_tipo = 'construtora';
        $user->name = $request->input('nome');
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

        $utilizador = new Construtora();
        $utilizador->nome_constr = $request->input('nome_constr');
        $utilizador->doc_vericacao = $request->input('doc_verificacao');
        //-------------------------upload de documento de verificacao--------------------------------
       /* if($request->hashFile('doc_verificacao') && $request->file('doc_verificacao')->isValid()){
            $requestDocument = $request->doc_verificacao;
            $extension = $requestDocument->extension();
            $docName = md5($requestDocument->doc_verificacao->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $request->doc_verificacao->move(public_path(), $docName);
            $utilizador->doc_verificacao = $docName;
        }*/
        $utilizador->sobre = $request->input('sobre');
        $utilizador->ano_criacao = $request->input('ano_criacao');
        $utilizador->endereco = $request->input('endereco');
        $utilizador->telefone = $request->input('telefone');
        $utilizador->id_user = $id_user;

        if($utilizador->save()){
            return redirect()->route('/')->with(['Mensagem' => 'Construtora Cadastrada com sucesso'], Response::HTTP_OK);
        }else{
            return redirect('/')->with(['Mensagem' => 'Erro no cadastro'], Response::HTTP_INTERNAL_SERVER_ERROR); 

        }
    }

     /**
     * Funcao para visualizar dados de uma Construtora.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showConstrutora($id){
        $utilizador = Construtora::findOrFail($id);
        return view('/', ['construtora' => $utilizador]);
    }

    /**
     * Funcao para trazer formulario para editar dados de uma construtora.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editConstrutora($id){
        $utilizador = User::findOrFail($id);
        $utilizador = Construtora::findOrFail($id);
        return view('editconstrutora', ['user' => $utilizador]);
    }

    /**
     * Funcao para actualizar dados de uma construtora.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateConstrutora(Request $request){
        $data = $request->all();
        // ---------------------upload de documento de verificacao------------------------------ 
        if($request->hashFile('doc_verificacao') && $request->file('doc_verificacao')->isValid()){
            $requestDocument = $request->doc_verificacao;
            $extension = $requestDocument->extension();
            $docName = md5($requestDocument->doc_verificacao->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $request->doc_verificacao->move(public_path(), $docName);
            $data['doc_verificacao'] = $docName;
        }
        if($request->all()){
            return redirect()->route('/')->with(['Mensagem' => 'Seus dados foram actualizados com sucesso'], Response::HTTP_OK);
        }else{
            return redirect('/')->with(['Mensagem' => 'Erro ao actualizar os dados'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Funcao para eliminar uma construtora.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyConstrutora($id){
        User::findOrFail($id)->delete();
        Construtora::findOrFail($id)->delete();

        return redirect()->route('/')->with(['Mensagem' => 'Construtora eliminada com sucesso', Response::HTTP_OK]);
    }

/**
 * ------------------------------------------------------fim construtoras --------------------------------------------------
 */    


/**
 * --------------------------------------------------------------------------------------------------------------------------
 * -----------------------------------------------------Agentes--------------------------------------------------------------
 * --------------------------------------------------------------------------------------------------------------------------
 */

    /**
     * Funcao para trazer todas Agencias.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAgente(){

        $utilizador = User::all();
        $utilizador = Agente::all();
        return view('listAgentes', ['user' => $utilizador]);
    }

     /**
     * Funcao para carregar formulario de cadastro de uma Agencia.
     *
     * @return \Illuminate\Http\Response
     */
    public function createAgente()
    {
        return view('/');
    }

    /**
     * Funcao para pesquisa de Agencias.
     *
     * @return \Illuminate\Http\Response
     */
    public function pesquisaAgencias()
    {
        //
        $search = request('search');
        if($search){
            $utilizador = Agente::where([
                ['nome', 'like', '%', $search. '%']
            ])->get();
        }
        return view('', ['user' => $utilizador, 'search' => $search]);
    }

    /**
     * Funcao para salvar dados de uma Agencia na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAgente(Request $request){
        $user = new User();
        $user->user_tipo = 'Agencia';
        $user->name = $request->input('nome');
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

        $utilizador = new Agente();
        $utilizador->nome_constr = $request->input('nome_agencia');
        $utilizador->doc_vericacao = $request->input('doc_verificacao');
        //-------------------------upload de documento de verificacao--------------------------------
       /* if($request->hashFile('doc_verificacao') && $request->file('doc_verificacao')->isValid()){
            $requestDocument = $request->doc_verificacao;
            $extension = $requestDocument->extension();
            $docName = md5($requestDocument->doc_verificacao->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $request->doc_verificacao->move(public_path(), $docName);
            $utilizador->doc_verificacao = $docName;
        }*/
        $utilizador->especializacao = $request->input('especializacao');
        $utilizador->ano_criacao = $request->input('ano_criacao');
        $utilizador->endereco = $request->input('endereco');
        $utilizador->telefone = $request->input('telefone');
        $utilizador->id_user = $id_user;

        if($utilizador->save()){
            return redirect()->route('/')->with(['Mensagem' => 'Agencia Cadastrada com sucesso'], Response::HTTP_OK);
        }else{
            return redirect('/')->with(['Mensagem' => 'Erro no cadastro'], Response::HTTP_INTERNAL_SERVER_ERROR); 

        }
    }

     /**
     * Funcao para visualizar dados de uma Agencia.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showAgencia($id){
        $utilizador = Agente::findOrFail($id);
        return view('/', ['agencia' => $utilizador]);
    }

    /**
     * Funcao para trazer formulario para editar dados de uma Agencia.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editAgencia($id){
        $utilizador = User::findOrFail($id);
        $utilizador = Agente::findOrFail($id);
        return view('editagencia', ['user' => $utilizador]);
    }

    /**
     * Funcao para actualizar dados de uma Agencia.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAgencia(Request $request){
        $data = $request->all();
        // ---------------------upload de documento de verificacao------------------------------ 
        if($request->hashFile('doc_verificacao') && $request->file('doc_verificacao')->isValid()){
            $requestDocument = $request->doc_verificacao;
            $extension = $requestDocument->extension();
            $docName = md5($requestDocument->doc_verificacao->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $request->doc_verificacao->move(public_path(), $docName);
            $data['doc_verificacao'] = $docName;
        }
        if($request->all()){
            return redirect()->route('/')->with(['Mensagem' => 'Seus dados foram actualizados com sucesso'], Response::HTTP_OK);
        }else{
            return redirect('/')->with(['Mensagem' => 'Erro ao actualizar os dados'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Funcao para eliminar uma Agencia.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyagencia($id){
        User::findOrFail($id)->delete();
        Agente::findOrFail($id)->delete();

        return redirect()->route('/')->with(['Mensagem' => 'Agencia eliminada com sucesso', Response::HTTP_OK]);
    }

/**
 * ------------------------------------------------------fim Agencias --------------------------------------------------
 */    


}


