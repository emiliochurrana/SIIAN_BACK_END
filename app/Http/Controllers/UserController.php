<?php

namespace App\Http\Controllers;

use App\Models\Agente;
use App\Models\Construtora;
use App\Models\Correctora;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

class UserController extends Controller
{

/**
 * ---------------------------------------------------------------------------------------
 * --------------------------------Correctoras------------------------------------------
 * ---------------------------------------------------------------------------------------
 */
    /**
     * Funcao para trazer todos Correctoras.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexCorrectora()
    {
        $utilizador = User::all();
        $utilizador = Correctora::all();
        return view('listCorrectoras', ['user' => $utilizador]);
    }
     
    /**
     * Funcao para carregar formulario de cadastro do Correctora.
     *
     * @return \Illuminate\Http\Response
     */
    public function createCorrectora()
    {
        return view('/');
    }

    /**
     * Funcao para pesquisa de Correctoras.
     *
     * @return \Illuminate\Http\Response
     */
    public function pesquisaCorrectoras()
    {
        //
        $search = request('search');
        if($search){
            $utilizador = Correctora::where([
                ['nome', 'like', '%', $search. '%']
            ])->get();
        }
        return view('', ['user' => $utilizador, 'search' => $search]);
    }

    /**
     * Funcao para salvar dados do Correctora na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function storeCorrectora(Request $request){
        $user = new User();
        $user->user_tipo = 'Correctora';
        $user->name = $request->input('nome');
        $user->email = $request->input('email');
        $user->username = $request->email;
        $user->password = Hash ::make($request->input('password'));
        $email = User::all()->where('email', '=', $user->email)->count();
        if($email > 0){
            $addCorrectora['success'] = false;
            $addCorrectora['mensagem'] = 'Esse email ja esta registado no sistema!';
            return response()->json($addCorrectora);
        }
        $user->save();
        $id_user = $user->id;
        
        $utilizador = new Correctora();
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
            return redirect()->route('/')->with(['Mensagem' => 'Correctora Cadastrado com sucesso'], Response::HTTP_OK);
        }else{
            return redirect('/')->with(['Mensagem' => 'Erro no cadastro'], Response::HTTP_INTERNAL_SERVER_ERROR);
        } 
    }

    /**
     * Funcao para visualizar dados de um Correctora.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showCorrectora($id)
    {
        $utilizador = Correctora::findOrFail($id);
        return view('/', ['correctora' => $utilizador]);
    }

    /**
     * Funcao para trazer formulario para editar dados do Correctora.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editCorrectora($id)
    {
        $utilizador = User::findOrFail($id);
        $utilizador = Correctora::findOrFail($id);
        return view('editCorrectora', ['user' => $utilizador]);
    }

    /**
     * Funcao para actualizar dados do Correctora.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCorrectora(Request $request)
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
     * Funcao para eliminar um Correctora.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyCorrectora($id)
    {
        User::findOrFail($id)->delete();
        Correctora::findOrFail($id)->delete();

        return redirect()->route('/')->with(['Mensagem' => 'Correctora eliminado com sucesso'], Response::HTTP_OK);
    }

/**
 * ----------------------------------------------fim Correctoras --------------------------------------------------------
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
            $addCorrectora['success'] = false;
            $addCorrectora['mensagem'] = 'Esse email ja esta registado no sistema!';
            return response()->json($addCorrectora);
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
            $addCorrectora['success'] = false;
            $addCorrectora['mensagem'] = 'Esse email ja esta registado no sistema!';
            return response()->json($addCorrectora);
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


