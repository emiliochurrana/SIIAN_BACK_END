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
 * ---------------------------------------------------------------------------------------------------------------------------------------
 * --------------------------------------------------------------------Correctoras--------------------------------------------------------
 * ---------------------------------------------------------------------------------------------------------------------------------------
 */
    /**
     * Funcao para trazer todos Correctoras.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexCorrectora()
    {
      $correctoras = User::with('correctoraUser')->where(['user_tipo' => 'correctora'])->get();
      return view('', compact('correctoras'));

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
            $correctora = Correctora::where([
                ['nome', 'like', '%', $search. '%']
            ])->get();
        }
        return view('', ['user' => $correctora, 'search' => $search]);
    }

    /**
     * Funcao para salvar dados do Correctora na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function storeCorrectora(Request $request){
        $user = new User();
        $user->user_tipo = 'correctora';
        $user->name = $request->input('name');
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
        
        $correctora = new Correctora();
        $correctora->id_user = $id_user;
        $correctora->tipo_doc = $request->input('tipo_doc');
        $correctora->data_nascimento = $request->input('data_nascimento');
        $correctora->num_doc = $request->input('num_doc');
        //-------------------------upload de documento de identificacao--------------------------------
        if($request->hashFile('doc_identificacao') && $request->file('doc_identificacao')->isValid()){
            $requestIdentificacao = $request->doc_identificacao;
            $extension = $requestIdentificacao->extension();
            $docName = md5($requestIdentificacao->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $requestIdentificacao->move(public_path('ficheiros/correctoras/identificacoes'), $docName);
            $correctora->doc_identificacao = $docName;
        }

        //-------------------------upload de foto do documento de identificacao--------------------------------
        if($request->hashFile('foto_doc') && $request->file('foto_doc')->isValid()){
            $requestFoto = $request->foto_doc;
            $extension = $requestFoto->extension();
            $fotoName = md5($requestFoto->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $requestFoto->move(public_path('ficheiros/correctoras/fotos'), $fotoName);
            $correctora->foto_doc = $fotoName;
        }
        $correctora->telefone = $request->input('telefone');
        $correctora->endereco = $request->input('endereco');
        
        if($correctora->save()){
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
        $user = User::findOrFail($id);
        $correctoras = $user->correctoraUser()->get();
        return view(' ', ['correctora' => $correctoras]);
    }

    /**
     * Funcao para trazer formulario para editar dados do Correctora.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editCorrectora($id)
    {
        $user = User::findOrFail($id);
        $correctoras = $user->correctoraUser()->get();
        return view('editCorrectora', ['correctoras' => $correctoras]);
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
        //-------------------------upload de documento de identificacao--------------------------------
        if($request->hashFile('doc_identificacao') && $request->file('doc_identificacao')->isValid()){
            $requestIdentificacao = $request->doc_identificacao;
            $extension = $requestIdentificacao->extension();
            $docName = md5($requestIdentificacao->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $requestIdentificacao->move(public_path('ficheiros/correctoras/identificacoes'), $docName);
            $data['doc_identificacao'] = $docName;
        }

        //-------------------------upload de foto do documento de identificacao--------------------------------
        if($request->hashFile('foto_doc') && $request->file('foto_doc')->isValid()){
            $requestFoto = $request->foto_doc;
            $extension = $requestFoto->extension();
            $fotoName = md5($requestFoto->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $requestFoto->move(public_path('ficheiros/correctoras/fotos'), $fotoName);
            $data['foto_doc'] = $fotoName;
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
        $user = User::findOrFail($id);
        $correctora = $user->correctoraUser()->get();
        $correctora->delete();

        return redirect()->route('/')->with(['Mensagem' => 'Correctora eliminado com sucesso'], Response::HTTP_OK);
    }

/**
 * ----------------------------------------------------------------------------------------------------------------------------------------------------
 * ---------------------------------------------------------------------fim Correctoras ---------------------------------------------------------------
 * ----------------------------------------------------------------------------------------------------------------------------------------------------
 */
     

/**
 * ----------------------------------------------------------------------------------------------------------------------------------------------------
 * -------------------------------------------------------------------------Construtoras---------------------------------------------------------------
 * ----------------------------------------------------------------------------------------------------------------------------------------------------
 */
    /**
     * Funcao para trazer todas construtoras.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexConstrutora(){
        $construtoras = User::with('construtoraUser')->where(['user_tipo' => 'construtora']);
        return view('', compact('construtoras'));
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
        $user->name = $request->input('name');
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

        $construtora = new Construtora();
        $construtora->num_alvara = $request->input('num_alvara');
        $construtora->num_nuit = $request->input('num_nuit');
        //-------------------------upload de Alvara--------------------------------
       if($request->hashFile('doc_alvara') && $request->file('doc_alvara')->isValid()){
            $requestAlvara = $request->doc_alvara;
            $extension = $requestAlvara->extension();
            $alvaraName = md5($requestAlvara->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $requestAlvara->move(public_path('ficheiros/agentes/alvaras'), $alvaraName);
            $construtora->doc_alvara = $alvaraName;
        }
        
          //-------------------------upload de Nuit--------------------------------
       if($request->hashFile('doc_nuit') && $request->file('doc_nuit')->isValid()){
        $requestNuit = $request->doc_nuit;
        $extension = $requestNuit->extension();
        $nuitName = md5($requestNuit->getClientOriginalName() . strtotime("now")) . "." . $extension;
        $requestNuit->move(public_path('ficheiros/agentes/nuit'), $nuitName);
        $construtora->doc_nuit = $nuitName;
        }
        //$utilizador->especializacao = $request->input('especializacao');
       // $utilizador->ano_criacao = $request->input('ano_criacao');
        $construtora->endereco = $request->input('endereco');
        $construtora->telefone = $request->input('telefone');
        $construtora->id_user = $id_user;

        if($construtora->save()){
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
        $user = User::findOrFail($id);
        $construtoras = $user->construtoraUser()->get();
        return view('/', ['construtoras' => $construtoras]);
    }

    /**
     * Funcao para trazer formulario para editar dados de uma construtora.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editConstrutora($id){
        $user = User::findOrFail($id);
        $construtoras = $user->construtoraUser()->get();
        return view('editconstrutora', ['construtoras' => $construtoras]);
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
         //-------------------------upload de Alvara--------------------------------
       if($request->hashFile('doc_alvara') && $request->file('doc_alvara')->isValid()){
            $requestAlvara = $request->doc_alvara;
            $extension = $requestAlvara->extension();
            $alvaraName = md5($requestAlvara->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $requestAlvara->move(public_path('ficheiros/agentes/alvaras'), $alvaraName);
            $data['doc_alvara'] = $alvaraName;
        }
    
        //-------------------------upload de Nuit--------------------------------
        if($request->hashFile('doc_nuit') && $request->file('doc_nuit')->isValid()){
            $requestNuit = $request->doc_nuit;
            $extension = $requestNuit->extension();
            $nuitName = md5($requestNuit->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $requestNuit->move(public_path('ficheiros/agentes/nuit'), $nuitName);
            $data['doc_nuit'] = $nuitName;
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
        $user = User::findOrFail($id);
        $construtora = $user->construtoraUser()->get();
        $construtora->delete();

        return redirect()->route('/')->with(['Mensagem' => 'Construtora eliminada com sucesso', Response::HTTP_OK]);
    }

/**
 * --------------------------------------------------------------------------------------------------------------------------------------------------
 * ------------------------------------------------------------------fim construtoras ---------------------------------------------------------------
 * --------------------------------------------------------------------------------------------------------------------------------------------------
 */    


/**
 * --------------------------------------------------------------------------------------------------------------------------------------------------
 * -----------------------------------------------------------------------Agentes--------------------------------------------------------------------
 * --------------------------------------------------------------------------------------------------------------------------------------------------
 */

    /**
     * Funcao para trazer todas Agencias.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAgente(){
        $agentes = User::with('agenteUser')->where(['user_tipo' => 'agente']);
        return view('', compact('agentes'));
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
        $user->user_tipo = 'agente';
        $user->name = $request->input('name');
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

        $agente = new Agente();
        $agente->num_alvara = $request->input('num_alvara');
        $agente->num_nuit = $request->input('num_nuit');
        //-------------------------upload de Alvara--------------------------------
       if($request->hashFile('doc_alvara') && $request->file('doc_alvara')->isValid()){
            $requestAlvara = $request->doc_alvara;
            $extension = $requestAlvara->extension();
            $alvaraName = md5($requestAlvara->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $requestAlvara->move(public_path('ficheiros/agentes/alvaras'), $alvaraName);
            $agente->doc_alvara = $alvaraName;
        }
        
          //-------------------------upload de Nuit--------------------------------
       if($request->hashFile('doc_nuit') && $request->file('doc_nuit')->isValid()){
            $requestNuit = $request->doc_nuit;
            $extension = $requestNuit->extension();
            $nuitName = md5($requestNuit->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $requestNuit->move(public_path('ficheiros/agentes/nuit'), $nuitName);
            $agente->doc_nuit = $nuitName;
        }
 
        $agente->endereco = $request->input('endereco');
        $agente->telefone = $request->input('telefone');
        $agente->id_user = $id_user;

        if($agente->save()){
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
        $user = User::findOrFail($id);
        $agentes = $user->agenteUser()->get();
        return view('editagencia', ['agentes' => $agentes]);
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
            //-------------------------upload de Alvara--------------------------------
        if($request->hashFile('doc_alvara') && $request->file('doc_alvara')->isValid()){
            $requestAlvara = $request->doc_alvara;
            $extension = $requestAlvara->extension();
            $alvaraName = md5($requestAlvara->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $requestAlvara->move(public_path('ficheiros/agentes/alvaras'), $alvaraName);
            $data['doc_alvara'] = $alvaraName;
        }
        
        //-------------------------upload de Nuit--------------------------------
        if($request->hashFile('doc_nuit') && $request->file('doc_nuit')->isValid()){
            $requestNuit = $request->doc_nuit;
            $extension = $requestNuit->extension();
            $nuitName = md5($requestNuit->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $requestNuit->move(public_path('ficheiros/agentes/nuit'), $nuitName);
            $data['doc_nuit'] = $nuitName;
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
    public function destroyAgencia($id){
        $user = User::findOrFail($id);
        $agente = $user->agenteUser()->get();
        $agente->delete();
        return redirect()->route('/')->with(['Mensagem' => 'Agencia eliminada com sucesso', Response::HTTP_OK]);
    }

/**
 * -------------------------------------------------------------------------------------------------------------------------------------------------------
 * ---------------------------------------------------------------------fim Agencias ---------------------------------------------------------------------
 * -------------------------------------------------------------------------------------------------------------------------------------------------------
 */    


}


