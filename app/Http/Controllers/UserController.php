<?php

namespace App\Http\Controllers;

use App\Models\Agente;
use App\Models\Construtora;
use App\Models\Correctora;
use App\Models\Funcionario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

/**
 * ---------------------------------------------------------------------------------------------------------------------------------------
 * --------------------------------------------------------------------Correctoras--------------------------------------------------------
 * ---------------------------------------------------------------------------------------------------------------------------------------
 */
    /**
     * Função para trazer todos Correctoras.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexCorrectora()
    {
        $search = request('search');

        if ($search) {
            $correctoras = User::where([
               ['user_tipo' => 'correctora'],  ['name', 'like', '%' .$search . '%']
            ])->get();
        }else{
            $correctoras = User::with('correctoraUser')->where(['user_tipo' => 'correctora'])->get();
        }
      return view('', ['$correctoras' => $correctoras, 'search' => $search]);

    }
     
    /**
     * Função para carregar formulario de cadastro do Correctora.
     *
     * @return \Illuminate\Http\Response
     */
    public function createCorrectora()
    {
        return view('/');
    }

    /**
     * Função para salvar dados do Correctora na base de dados.
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
        $pass = $request->password;
        $confirPass = $request->confirm_password;
        if($pass == $confirPass){
            if(strlen($pass) >= 6){
            $user->password = Hash ::make($request->password);
            }else{
                return redirect()->back()->with(['msgErrorPass' => 'A palavra passe de ter no minimo 6 digitos']);
            }
        }else{
            return redirect()->back()->with(['msgPass' => 'A palavra passe nao coincide']);
        }
          
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
     * Função para visualizar dados de um Correctora.
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
     * Função para trazer formulario para editar dados do Correctora.
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
     * Função para actualizar dados do Correctora.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCorrectora(Request $request)
     {
        $user = auth()->user();
        $correctora = $user->correctoraUser;
        $userPassword = $user->password;

        if($request->password_actual != ""){
            $newPass = $request->password;
            $confirPass = $request->confirm_password;
            $name = $request->name;
            $email = $request->email;
            $username = $request->username;
            $tipo_doc = $request->tipo_doc;
            $data_nascimento = $request->data_nascimento;
            $num_doc = $request->num_doc;
            $endereco = $request->endereco;
            $telefone = $request->telefone;
            if(Hash::check($request->password_actual, $userPassword)){
                if($newPass == $confirPass){
                    if(strlen($newPass) >= 6){
                        $user->password = Hash::make($request->password);
                        DB::table('users')->where('id', $user->id)->update(['password' => $user->password]);
                        DB::table('users')->where('id', $user->id)->update(['name' => $name]);
                        DB::table('users')->where('id', $user->id)->update(['email' => $email]);
                        DB::table('users')->where('id', $user->id)->update(['username' => $username]);
            
                        //-------------------------upload de documento de identificacao--------------------------------
                        if($request->hashFile('doc_identificacao') && $request->file('doc_identificacao')->isValid()){
                            $requestIdentificacao = $request->doc_identificacao;
                            $extension = $requestIdentificacao->extension();
                            $docName = md5($requestIdentificacao->getClientOriginalName() . strtotime("now")) . "." . $extension;
                            $requestIdentificacao->move(public_path('ficheiros/correctoras/identificacoes'), $docName);
                            $doc_identificacao = $docName;
                            DB::table('correctoras')->where('id', $correctora->id)->update(['doc_identificacao' => $doc_identificacao]);
            
                        }
                        
                       //-------------------------upload de foto do documento de identificacao--------------------------------
                        //-------------------------upload de foto do documento de identificacao--------------------------------
                        if($request->hashFile('foto_doc') && $request->file('foto_doc')->isValid()){
                            $requestFoto = $request->foto_doc;
                            $extension = $requestFoto->extension();
                            $fotoName = md5($requestFoto->getClientOriginalName() . strtotime("now")) . "." . $extension;
                            $requestFoto->move(public_path('ficheiros/correctoras/fotos'), $fotoName);
                            $foto_doc = $fotoName;
                            DB::table('correctoras')->where('id', $correctora->id)->update(['foto_doc' => $foto_doc]);
            
                        }
                        DB::table('correctoras')->where('id', $correctora->id)->update(['tipo_doc' => $tipo_doc]);
                        DB::table('correctoras')->where('id', $correctora->id)->update(['data_nascimento' => $data_nascimento]);
                        DB::table('correctoras')->where('id', $correctora->id)->update(['num_doc' => $num_doc]);
                        DB::table('correctoras')->where('id', $correctora->id)->update(['telefone' => $telefone]);
                        DB::table('correctoras')->where('id', $correctora->id)->update(['endereco' => $endereco]);
                        return redirect()->route('')->with(['msgPassSucess' => 'A palavra passe foi combinada correctamente']);
                    }else{
                        return redirect()->back()->with(['msgErrorPass' => 'A palavra passe deve ter no minimo 6 digitos']);
                    }
                }else{
                    return redirect()->back()->with(['msgIncorrecta' => 'Por favor verifique a palavra passe nao coincide']);
                }

            }else{
                return redirect()->back()->with(['password_actual' => 'A palavra passe actual nao coincide com a nova']);
            }
        }else{
            $name = $request->name;
            $email = $request->email;
            $username = $request->username;
            $tipo_doc = $request->tipo_doc;
            $data_nascimento = $request->data_nascimento;
            $num_doc = $request->num_doc;
            $endereco = $request->endereco;
            $telefone = $request->telefone;
            DB::table('users')->where('id', $user->id)->update(['name' => $name]);
            DB::table('users')->where('id', $user->id)->update(['email' => $email]);
            DB::table('users')->where('id', $user->id)->update(['username' => $username]);

            //-------------------------upload de documento de identificacao--------------------------------
            if($request->hashFile('doc_identificacao') && $request->file('doc_identificacao')->isValid()){
                $requestIdentificacao = $request->doc_identificacao;
                $extension = $requestIdentificacao->extension();
                $docName = md5($requestIdentificacao->getClientOriginalName() . strtotime("now")) . "." . $extension;
                $requestIdentificacao->move(public_path('ficheiros/correctoras/identificacoes'), $docName);
                $doc_identificacao = $docName;
                DB::table('correctoras')->where('id', $correctora->id)->update(['doc_identificacao' => $doc_identificacao]);

            }
            
           //-------------------------upload de foto do documento de identificacao--------------------------------
            //-------------------------upload de foto do documento de identificacao--------------------------------
            if($request->hashFile('foto_doc') && $request->file('foto_doc')->isValid()){
                $requestFoto = $request->foto_doc;
                $extension = $requestFoto->extension();
                $fotoName = md5($requestFoto->getClientOriginalName() . strtotime("now")) . "." . $extension;
                $requestFoto->move(public_path('ficheiros/correctoras/fotos'), $fotoName);
                $foto_doc = $fotoName;
                DB::table('correctoras')->where('id', $correctora->id)->update(['foto_doc' => $foto_doc]);

            }
            DB::table('correctoras')->where('id', $correctora->id)->update(['tipo_doc' => $tipo_doc]);
            DB::table('correctoras')->where('id', $correctora->id)->update(['data_nascimento' => $data_nascimento]);
            DB::table('correctoras')->where('id', $correctora->id)->update(['num_doc' => $num_doc]);
            DB::table('correctoras')->where('id', $correctora->id)->update(['telefone' => $telefone]);
            DB::table('correctoras')->where('id', $correctora->id)->update(['endereco' => $endereco]);

            return redirect()->route('')->with(['msgSucess' => 'Dados actualizados com sucesso!']);
        }
        return redirect()->back()->with(['msgError' => 'Erro ao actualizar os dados']);

        
    }
    
    /**
     * Função para eliminar um Correctora.
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
     * Função para trazer todas construtoras.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexConstrutora(){
        $search = request('search');

        if ($search) {
            $construtoras = User::where([
                ['user_tipo' => 'construtora'], ['name', 'like', '%' .$search . '%']
            ])->get();
        }else{
            $construtoras = User::with('construtoraUser')->where(['user_tipo' => 'construtora']);
        }
        return view('', ['construtoras' => $construtoras, 'search' => $search]);
    }

     /**
     * Função para carregar formulario de cadastro de uma construtora.
     *
     * @return \Illuminate\Http\Response
     */
    public function createConstrutora()
    {
        return view('/');
    }

    /**
     * Função para salvar dados de uma Construtora na base de dados.
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
        $pass = $request->password;
        $confirPass = $request->confirm_password;
        if($pass == $confirPass){
            if(strlen($pass) >= 6){
            $user->password = Hash ::make($request->password);
            }else{
                return redirect()->back()->with(['msgErrorPass' => 'A palavra passe de ter no minimo 6 digitos']);
            }
        }else{
            return redirect()->back()->with(['msgPass' => 'A palavra passe nao coincide']);
        }
          
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
     * Função para visualizar dados de uma Construtora.
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
     * Função para trazer formulario para editar dados de uma construtora.
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
     * Função para actualizar dados de uma construtora.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateConstrutora(Request $request){
        $user = auth()->user();
        $construtora = $user->construtoraUser;
        $userPassword = $user->password;

        if($request->password_actual != ""){
            $newPass = $request->password;
            $confirPass = $request->confirm_password;
            $name = $request->name;
            $email = $request->email;
            $username = $request->username;
            $alvara = $request->num_alvara;
            $nuit = $request->num_nuit;
            $endereco = $request->endereco;
            $telefone = $request->telefone;
            if(Hash::check($request->password_actual, $userPassword)){
                if($newPass == $confirPass){
                    if(strlen($newPass) >= 6){
                        $user->password = Hash::make($request->password);
                        DB::table('users')->where('id', $user->id)->update(['password' => $user->password]);
                        DB::table('users')->where('id', $user->id)->update(['name' => $name]);
                        DB::table('users')->where('id', $user->id)->update(['email' => $email]);
                        DB::table('users')->where('id', $user->id)->update(['username' => $username]);

                         //-------------------------upload de Alvara--------------------------------
                        if($request->hashFile('doc_alvara') && $request->file('doc_alvara')->isValid()){
                            $requestAlvara = $request->doc_alvara;
                            $extension = $requestAlvara->extension();
                            $alvaraName = md5($requestAlvara->getClientOriginalName() . strtotime("now")) . "." . $extension;
                            $requestAlvara->move(public_path('ficheiros/construtoras/alvaras'), $alvaraName);
                            $doc_alvara= $alvaraName;
                            DB::table('construtoras')->where('id', $construtora ->id)->update(['doc_alvara' => $doc_alvara]);

                        }
                        
                                //-------------------------upload de Nuit--------------------------------
                        if($request->hashFile('doc_nuit') && $request->file('doc_nuit')->isValid()){
                            $requestNuit = $request->doc_nuit;
                            $extension = $requestNuit->extension();
                            $nuitName = md5($requestNuit->getClientOriginalName() . strtotime("now")) . "." . $extension;
                            $requestNuit->move(public_path('ficheiros/construtoras/nuit'), $nuitName);
                            $doc_nuit = $nuitName;
                            DB::table('construtoras')->where('id', $construtora ->id)->update(['doc_nuit' => $doc_nuit]);

                        }
                        DB::table('construtoras')->where('id', $construtora ->id)->update(['num_alvara' => $alvara]);
                        DB::table('construtoras')->where('id', $construtora ->id)->update(['num_nuit' => $nuit]);
                        DB::table('construtoras')->where('id', $construtora ->id)->update(['telefone' => $telefone]);
                        DB::table('construtoras')->where('id', $construtora ->id)->update(['endereco' => $endereco]);
                        return redirect()->route('')->with(['msgPassSucess' => 'A palavra passe foi combinada correctamente']);
                    }else{
                        return redirect()->back()->with(['msgErrorPass' => 'A palavra passe deve ter no minimo 6 digitos']);
                    }
                }else{
                    return redirect()->back()->with(['msgIncorrecta' => 'Por favor verifique a palavra passe nao coincide']);
                }

            }else{
                return redirect()->back()->with(['password_actual' => 'A palavra passe actual nao coincide com a nova']);
            }
        }else{
            $name = $request->name;
            $email = $request->email;
            $username = $request->username;
            $alvara = $request->num_alvara;
            $nuit = $request->num_nuit;
            $endereco = $request->endereco;
            $telefone = $request->telefone;
            DB::table('users')->where('id', $user->id)->update(['name' => $name]);
            DB::table('users')->where('id', $user->id)->update(['email' => $email]);
            DB::table('users')->where('id', $user->id)->update(['username' => $username]);

             //-------------------------upload de Alvara--------------------------------
            if($request->hashFile('doc_alvara') && $request->file('doc_alvara')->isValid()){
                $requestAlvara = $request->doc_alvara;
                $extension = $requestAlvara->extension();
                $alvaraName = md5($requestAlvara->getClientOriginalName() . strtotime("now")) . "." . $extension;
                $requestAlvara->move(public_path('ficheiros/construtoras/alvaras'), $alvaraName);
                $doc_alvara= $alvaraName;
                DB::table('construtoras')->where('id', $construtora ->id)->update(['doc_alvara' => $doc_alvara]);

            }
            
            //-------------------------upload de Nuit--------------------------------
            if($request->hashFile('doc_nuit') && $request->file('doc_nuit')->isValid()){
                $requestNuit = $request->doc_nuit;
                $extension = $requestNuit->extension();
                $nuitName = md5($requestNuit->getClientOriginalName() . strtotime("now")) . "." . $extension;
                $requestNuit->move(public_path('ficheiros/construtoras/nuit'), $nuitName);
                $doc_nuit = $nuitName;
                DB::table('construtoras')->where('id', $construtora ->id)->update(['doc_nuit' => $doc_nuit]);

            }
            DB::table('construtoras')->where('id', $construtora ->id)->update(['num_alvara' => $alvara]);
            DB::table('construtoras')->where('id', $construtora ->id)->update(['num_nuit' => $nuit]);
            DB::table('construtoras')->where('id', $construtora ->id)->update(['telefone' => $telefone]);
            DB::table('construtoras')->where('id', $construtora ->id)->update(['endereco' => $endereco]);

            return redirect()->route('')->with(['msgSucess' => 'Dados actualizados com sucesso!']);
        }
        return redirect()->back()->with(['msgError' => 'Erro ao actualizar os dados']);
    }

    /**
     * Função para eliminar uma construtora.
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
     * Função para trazer todas Agencias.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAgente(){
        $search = request('search');

        if ($search) {
            $agentes = User::where([
              ['user_tipo' => 'agente'],  ['name', 'like', '%' .$search. '%']
            ])->get();
        }else{
        $agentes = User::with('agenteUser')->where(['user_tipo' => 'agente']);
        }
        return view('', ['agentes' => $agentes, 'search' => $search]);
    }

     /**
     * Função para carregar formulario de cadastro de uma Agencia.
     *
     * @return \Illuminate\Http\Response
     */
    public function createAgente()
    {
        return view('/');
    }

    /**
     * Função para salvar dados de uma Agencia na base de dados.
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
        $pass = $request->password;
        $confirPass = $request->confirm_password;
        if($pass == $confirPass){
            if(strlen($pass) >= 6){
            $user->password = Hash ::make($request->password);
            }else{
                return redirect()->back()->with(['msgErrorPass' => 'A palavra passe de ter no minimo 6 digitos']);
            }
        }else{
            return redirect()->back()->with(['msgPass' => 'A palavra passe nao coincide']);
        }
            
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
     * Função para visualizar dados de uma Agencia.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showAgencia($id){
        $user = User::findOrFail($id);
        $agencias = $user->agenteUser()->get();
        return view('/', ['agencias' => $agencias]);
    }

    /**
     * Função para trazer formulario para editar dados de uma Agencia.
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
     * Função para actualizar dados de uma Agencia.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAgencia(Request $request){
        $user = auth()->user();
        $agente = $user->agenteUser;
        $userPassword = $user->password;

        if($request->password_actual != ""){
            $newPass = $request->password;
            $confirPass = $request->confirm_password;
            $name = $request->name;
            $email = $request->email;
            $username = $request->username;
            $alvara = $request->num_alvara;
            $nuit = $request->num_nuit;
            $endereco = $request->endereco;
            $telefone = $request->telefone;
            if(Hash::check($request->password_actual, $userPassword)){
                if($newPass == $confirPass){
                    if(strlen($newPass) >= 6){
                        $user->password = Hash::make($request->password);
                        DB::table('users')->where('id', $user->id)->update(['password' => $user->password]);
                        DB::table('users')->where('id', $user->id)->update(['name' => $name]);
                        DB::table('users')->where('id', $user->id)->update(['email' => $email]);
                        DB::table('users')->where('id', $user->id)->update(['username' => $username]);

                         //-------------------------upload de Alvara--------------------------------
                        if($request->hashFile('doc_alvara') && $request->file('doc_alvara')->isValid()){
                            $requestAlvara = $request->doc_alvara;
                            $extension = $requestAlvara->extension();
                            $alvaraName = md5($requestAlvara->getClientOriginalName() . strtotime("now")) . "." . $extension;
                            $requestAlvara->move(public_path('ficheiros/agentes/alvaras'), $alvaraName);
                            $doc_alvara= $alvaraName;
                            DB::table('agentes')->where('id', $agente->id)->update(['doc_alvara' => $doc_alvara]);

                        }
                        
                                //-------------------------upload de Nuit--------------------------------
                        if($request->hashFile('doc_nuit') && $request->file('doc_nuit')->isValid()){
                            $requestNuit = $request->doc_nuit;
                            $extension = $requestNuit->extension();
                            $nuitName = md5($requestNuit->getClientOriginalName() . strtotime("now")) . "." . $extension;
                            $requestNuit->move(public_path('ficheiros/agentes/nuit'), $nuitName);
                            $doc_nuit = $nuitName;
                            DB::table('agentes')->where('id', $agente->id)->update(['doc_nuit' => $doc_nuit]);

                        }
                        DB::table('agentes')->where('id', $agente->id)->update(['num_alvara' => $alvara]);
                        DB::table('agentes')->where('id', $agente->id)->update(['num_nuit' => $nuit]);
                        DB::table('agentes')->where('id', $agente->id)->update(['telefone' => $telefone]);
                        DB::table('agentes')->where('id', $agente->id)->update(['endereco' => $endereco]);
                        return redirect()->route('')->with(['msgPassSucess' => 'A palavra passe foi combinada correctamente']);
                    }else{
                        return redirect()->back()->with(['msgErrorPass' => 'A palavra passe deve ter no minimo 6 digitos']);
                    }
                }else{
                    return redirect()->back()->with(['msgIncorrecta' => 'Por favor verifique a palavra passe nao coincide']);
                }

            }else{
                return redirect()->back()->with(['password_actual' => 'A palavra passe actual nao coincide com a nova']);
            }
        }else{
            $name = $request->name;
            $email = $request->email;
            $username = $request->username;
            $alvara = $request->num_alvara;
            $nuit = $request->num_nuit;
            $endereco = $request->endereco;
            $telefone = $request->telefone;
            DB::table('users')->where('id', $user->id)->update(['name' => $name]);
            DB::table('users')->where('id', $user->id)->update(['email' => $email]);
            DB::table('users')->where('id', $user->id)->update(['username' => $username]);

             //-------------------------upload de Alvara--------------------------------
            if($request->hashFile('doc_alvara') && $request->file('doc_alvara')->isValid()){
                $requestAlvara = $request->doc_alvara;
                $extension = $requestAlvara->extension();
                $alvaraName = md5($requestAlvara->getClientOriginalName() . strtotime("now")) . "." . $extension;
                $requestAlvara->move(public_path('ficheiros/agentes/alvaras'), $alvaraName);
                $doc_alvara= $alvaraName;
                DB::table('agentes')->where('id', $agente->id)->update(['doc_alvara' => $doc_alvara]);

            }
            
            //-------------------------upload de Nuit--------------------------------
            if($request->hashFile('doc_nuit') && $request->file('doc_nuit')->isValid()){
                $requestNuit = $request->doc_nuit;
                $extension = $requestNuit->extension();
                $nuitName = md5($requestNuit->getClientOriginalName() . strtotime("now")) . "." . $extension;
                $requestNuit->move(public_path('ficheiros/agentes/nuit'), $nuitName);
                $doc_nuit = $nuitName;
                DB::table('agentes')->where('id', $agente->id)->update(['doc_nuit' => $doc_nuit]);

            }
            DB::table('agentes')->where('id', $agente->id)->update(['num_alvara' => $alvara]);
            DB::table('agentes')->where('id', $agente->id)->update(['num_nuit' => $nuit]);
            DB::table('agentes')->where('id', $agente->id)->update(['telefone' => $telefone]);
            DB::table('agentes')->where('id', $agente->id)->update(['endereco' => $endereco]);
            return redirect()->route('')->with(['msgSucess' => 'Dados actualizados com sucesso!']);
        }
        return redirect()->back()->with(['msgError' => 'Erro ao actualizar os dados']);
    }

    /**
     * Função para eliminar uma Agencia.
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

 
 /**
 * -------------------------------------------------------------------------------------------------------------------------------------------------------
 * ---------------------------------------------------------------------Funcionarios ---------------------------------------------------------------------
 * -------------------------------------------------------------------------------------------------------------------------------------------------------
 */  

   /**
     * Função para trazer todos funcionarios.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexFuncionario(){

        $search = request('search');

        if ($search) {
            $userlog = auth()->user()->id;
            $funcionarios = Funcionario::where([
                   ['id_empresa', $userlog], ['name', 'like', '%' .$search. '%']
            ])->get();
        }else{
            $user = auth()->user();
        //$funcionarios = User::with('funcionarioUser')->where(['user_tipo' => 'funcionario']);
            $funcionarios = $user->funcionarioEmpresa;
        }
        return view('', ['funcionarios' => $funcionarios, 'search' => $search]);
    }

     /**
     * Função para carregar formulario de cadastro de um funcionario.
     *
     * @return \Illuminate\Http\Response
     */
    public function createFuncionario()
    {
        return view('/');
    }

    /**
     * Função para salvar dados de um Funcionario na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeFuncionario(Request $request){
        $user = new User();
        $user->user_tipo = 'funcionario';
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->username = $request->email;
        $pass = $request->password;
        $confirPass = $request->confirm_password;
        if($pass == $confirPass){
            if(strlen($pass) >= 6){
            $user->password = Hash ::make($request->password);
            }else{
                return redirect()->back()->with(['msgErrorPass' => 'A palavra passe de ter no minimo 6 digitos']);
            }
        }else{
            return redirect()->back()->with(['msgPass' => 'A palavra passe nao coincide']);
        }
          
        $email = User::all()->where('email', '=', $user->email)->count();
        if($email > 0){
            $addCorrectora['success'] = false;
            $addCorrectora['mensagem'] = 'Esse email ja esta registado no sistema!';
            return response()->json($addCorrectora);
        }
        $user->save();
        $id_user = $user->id;

        $funcionario = new Funcionario();
        $funcionario->name = $request->input('name');
        $funcionario->data_nascimento = $request->input('data_nascimento');
        //-------------------------upload de documento de identificacao--------------------------------
       if($request->hashFile('doc_identificacao') && $request->file('doc_identificacao')->isValid()){
            $requestDoc = $request->doc_identificacao;
            $extension = $requestDoc->extension();
            $docName = md5($requestDoc->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $requestDoc->move(public_path('ficheiros/funcionarios/identificacao'), $docName);
            $funcionario->doc_identificacao = $docName;
        }
        
          //-------------------------upload de Curriculum--------------------------------
       if($request->hashFile('curriculum') && $request->file('curriculum')->isValid()){
            $requestCurriculum = $request->curriculum;
            $extension = $requestCurriculum->extension();
            $curriculumName = md5($requestCurriculum->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $requestCurriculum->move(public_path('ficheiros/funcionarios/curriculum'), $curriculumName);
            $funcionario->curriculum = $curriculumName;
        }
 
        $funcionario->endereco = $request->input('endereco');
        $funcionario->telefone = $request->telefone;
        $funcionario->id_user = $id_user;
        $userauth = auth()->user();
        $funcionario->id_empresa =$userauth->id;

        if($funcionario->save()){
            return redirect()->route('/')->with(['Mensagem' => 'Funcionario cadastrado com sucesso'], Response::HTTP_OK);
        }else{
            return redirect('/')->with(['Mensagem' => 'Erro no cadastro'], Response::HTTP_INTERNAL_SERVER_ERROR); 

        }
    }

     /**
     * Função para visualizar dados de uma Agencia.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showFuncionario($id){
        $user = User::findOrFail($id);
        $funcionarios = $user->funcionarioUser()->get();
        return view('/', ['funcionarios' => $funcionarios]);
    }


      /**
     * Função para carregar o perfil do Funcionario.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function perfilFuncionario($id){

        $user = User::findOrFail($id);
        $funcionario = $user->funcionarioUser()->get();

        return view('', ['funcionario' => $funcionario]);
    }


    /**
     * Função para trazer formulario para editar dados de uma Agencia.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editFuncionario($id){
        $user = User::findOrFail($id);
        $funcionarios = $user->agenteUser()->get();
        return view('editagencia', ['funcionarios' => $funcionarios]);
    }

    /**
     * Função para actualizar dados de uma Agencia.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateFuncionario(Request $request){
        $user = User::findOrFail($request->id);
        $funcionario = $user->funcionarioUser;
        $userPassword = $user->password;

        if($request->password_actual != ""){
            $newPass = $request->password;
            $confirPass = $request->confirm_password;
            $name = $request->name;
            $email = $request->email;
            $username = $request->username;
            $data_nascimento = $request->data_nascimento;
            $endereco = $request->endereco;
            $telefone = $request->telefone;
            if(Hash::check($request->password_actual, $userPassword)){
                if($newPass == $confirPass){
                    if(strlen($newPass) >= 6){
                        $user->password = Hash::make($request->password);
                        DB::table('users')->where('id', $user->id)->update(['password' => $user->password]);
                        $data = $request->all('name', 'email', 'username');
                        $doc_identificacao = $request->all('doc_identificacao');
                        $curriculum = $request->all('curriculum');

                           //-------------------------upload de documento de identificacao--------------------------------
                        if($request->hashFile('doc_identificacao') && $request->file('doc_identificacao')->isValid()){
                            $requestDoc = $request->doc_identificacao;
                            $extension = $requestDoc->extension();
                            $docName = md5($requestDoc->getClientOriginalName() . strtotime("now")) . "." . $extension;
                            $requestDoc->move(public_path('ficheiros/funcionarios/identificacao'), $docName);
                            $doc_identificacao['doc_identificacao'] = $docName;
                            $funcionario->update($doc_identificacao);

                        }
                        
                         //-------------------------upload de Curriculum--------------------------------
                        if($request->hashFile('curriculum') && $request->file('curriculum')->isValid()){
                            $requestCurriculum = $request->curriculum;
                            $extension = $requestCurriculum->extension();
                            $curriculumName = md5($requestCurriculum->getClientOriginalName() . strtotime("now")) . "." . $extension;
                            $requestCurriculum->move(public_path('ficheiros/funcionarios/curriculum'), $curriculumName);
                            $curriculum['curriculum'] = $curriculumName;
                            $funcionario->update($curriculum);
                        }
                        $user->update($data);
                        DB::table('funcionarios')->where('id', $funcionario->id)->update(['data_nascimento' => $data_nascimento]);
                        DB::table('funcionarios')->where('id', $funcionario->id)->update(['telefone' => $telefone]);
                        DB::table('funcionarios')->where('id', $funcionario->id)->update(['endereco' => $endereco]);

                        return redirect()->route('')->with(['msgPassSucess' => 'A palavra passe foi combinada correctamente']);
                    }else{
                        return redirect()->back()->with(['msgErrorPass' => 'A palavra passe deve ter no minimo 6 digitos']);
                    }
                }else{
                    return redirect()->back()->with(['msgIncorrecta' => 'Por favor verifique a palavra passe nao coincide']);
                }

            }else{
                return redirect()->back()->with(['password_actual' => 'A palavra passe actual nao coincide com a nova']);
            }
        }else{
            $name = $request->name;
            $email = $request->email;
            $username = $request->username;
            $data_nascimento = $request->data_nascimento;
            $endereco = $request->endereco;
            $telefone = $request->telefone;
            DB::table('users')->where('id', $user->id)->update(['password' => $user->password]);
            $data = $request->all('name', 'email', 'username');
            $doc_identificacao = $request->all('doc_identificacao');
            $curriculum = $request->all('curriculum');

               //-------------------------upload de documento de identificacao--------------------------------
            if($request->hashFile('doc_identificacao') && $request->file('doc_identificacao')->isValid()){
                $requestDoc = $request->doc_identificacao;
                $extension = $requestDoc->extension();
                $docName = md5($requestDoc->getClientOriginalName() . strtotime("now")) . "." . $extension;
                $requestDoc->move(public_path('ficheiros/funcionarios/identificacao'), $docName);
                $doc_identificacao['doc_identificacao'] = $docName;
                $funcionario->update($doc_identificacao);

            }
            
             //-------------------------upload de Curriculum--------------------------------
            if($request->hashFile('curriculum') && $request->file('curriculum')->isValid()){
                $requestCurriculum = $request->curriculum;
                $extension = $requestCurriculum->extension();
                $curriculumName = md5($requestCurriculum->getClientOriginalName() . strtotime("now")) . "." . $extension;
                $requestCurriculum->move(public_path('ficheiros/funcionarios/curriculum'), $curriculumName);
                $curriculum['curriculum'] = $curriculumName;
                $funcionario->update($curriculum);
            }
            $user->update($data);
            DB::table('funcionarios')->where('id', $funcionario->id)->update(['data_nascimento' => $data_nascimento]);
            DB::table('funcionarios')->where('id', $funcionario->id)->update(['telefone' => $telefone]);
            DB::table('funcionarios')->where('id', $funcionario->id)->update(['endereco' => $endereco]);

            return redirect()->route('')->with(['msgSucess' => 'Dados actualizados com sucesso!']);
        }
        return redirect()->back()->with(['msgError' => 'Erro ao actualizar os dados']);

    }

          /**
     * Função para trazer formulario para editar dados de uma Agencia.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editPerfilFuncionario($id){
        $user = User::findOrFail($id);
        $funcionarios = $user->agenteUser()->get();
        return view('editagencia', ['funcionarios' => $funcionarios]);
    }

    public function updatePerfilFuncionario(Request $request){
        $user = auth()->user();
        $funcionario = $user->funcionarioUser;
        $userPassword = $user->password;

        if($request->password_actual != ""){
            $newPass = $request->password;
            $confirPass = $request->confirm_password;
            $name = $request->name;
            $email = $request->email;
            $username = $request->username;
            $data_nascimento = $request->data_nascimento;
            $endereco = $request->endereco;
            $telefone = $request->telefone;
            if(Hash::check($request->password_actual, $userPassword)){
                if($newPass == $confirPass){
                    if(strlen($newPass) >= 6){
                        $user->password = Hash::make($request->password);
                        DB::table('users')->where('id', $user->id)->update(['password' => $user->password]);
                        DB::table('users')->where('id', $user->id)->update(['name' => $name]);
                        DB::table('users')->where('id', $user->id)->update(['email' => $email]);
                        DB::table('users')->where('id', $user->id)->update(['username' => $username]);

                           //-------------------------upload de documento de identificacao--------------------------------
                        if($request->hashFile('doc_identificacao') && $request->file('doc_identificacao')->isValid()){
                            $requestDoc = $request->doc_identificacao;
                            $extension = $requestDoc->extension();
                            $docName = md5($requestDoc->getClientOriginalName() . strtotime("now")) . "." . $extension;
                            $requestDoc->move(public_path('ficheiros/funcionarios/identificacao'), $docName);
                            $doc_identificacao = $docName;
                            DB::table('funcionarios')->where('id', $funcionario->id)->update(['doc_identificacao' => $doc_identificacao]);

                        }
                        
                         //-------------------------upload de Curriculum--------------------------------
                        if($request->hashFile('curriculum') && $request->file('curriculum')->isValid()){
                            $requestCurriculum = $request->curriculum;
                            $extension = $requestCurriculum->extension();
                            $curriculumName = md5($requestCurriculum->getClientOriginalName() . strtotime("now")) . "." . $extension;
                            $requestCurriculum->move(public_path('ficheiros/funcionarios/curriculum'), $curriculumName);
                            $curriculum = $curriculumName;
                            DB::table('funcionarios')->where('id', $funcionario->id)->update(['curriculum' => $curriculum]);
                        }
                        DB::table('funcionarios')->where('id', $funcionario->id)->update(['data_nascimento' => $data_nascimento]);
                        DB::table('funcionarios')->where('id', $funcionario->id)->update(['telefone' => $telefone]);
                        DB::table('funcionarios')->where('id', $funcionario->id)->update(['endereco' => $endereco]);

                        return redirect()->route('')->with(['msgPassSucess' => 'A palavra passe foi combinada correctamente']);
                    }else{
                        return redirect()->back()->with(['msgErrorPass' => 'A palavra passe deve ter no minimo 6 digitos']);
                    }
                }else{
                    return redirect()->back()->with(['msgIncorrecta' => 'Por favor verifique a palavra passe nao coincide']);
                }

            }else{
                return redirect()->back()->with(['password_actual' => 'A palavra passe actual nao coincide com a nova']);
            }
        }else{
            $name = $request->name;
            $email = $request->email;
            $username = $request->username;
            $data_nascimento = $request->data_nascimento;
            $endereco = $request->endereco;
            $telefone = $request->telefone;
            DB::table('users')->where('id', $user->id)->update(['password' => $user->password]);
            DB::table('users')->where('id', $user->id)->update(['name' => $name]);
            DB::table('users')->where('id', $user->id)->update(['email' => $email]);
            DB::table('users')->where('id', $user->id)->update(['username' => $username]);

               //-------------------------upload de documento de identificacao--------------------------------
            if($request->hashFile('doc_identificacao') && $request->file('doc_identificacao')->isValid()){
                $requestDoc = $request->doc_identificacao;
                $extension = $requestDoc->extension();
                $docName = md5($requestDoc->getClientOriginalName() . strtotime("now")) . "." . $extension;
                $requestDoc->move(public_path('ficheiros/funcionarios/identificacao'), $docName);
                $doc_identificacao = $docName;
                DB::table('funcionarios')->where('id', $funcionario->id)->update(['doc_identificacao' => $doc_identificacao]);

            }
            
             //-------------------------upload de Curriculum--------------------------------
            if($request->hashFile('curriculum') && $request->file('curriculum')->isValid()){
                $requestCurriculum = $request->curriculum;
                $extension = $requestCurriculum->extension();
                $curriculumName = md5($requestCurriculum->getClientOriginalName() . strtotime("now")) . "." . $extension;
                $requestCurriculum->move(public_path('ficheiros/funcionarios/curriculum'), $curriculumName);
                $curriculum = $curriculumName;
                DB::table('funcionarios')->where('id', $funcionario->id)->update(['curriculum' => $curriculum]);
            }
            DB::table('funcionarios')->where('id', $funcionario->id)->update(['data_nascimento' => $data_nascimento]);
            DB::table('funcionarios')->where('id', $funcionario->id)->update(['telefone' => $telefone]);
            DB::table('funcionarios')->where('id', $funcionario->id)->update(['endereco' => $endereco]);

            return redirect()->route('')->with(['msgSucess' => 'Dados actualizados com sucesso!']);
        }
        return redirect()->back()->with(['msgError' => 'Erro ao actualizar os dados']);
    }

    /**
     * Função para eliminar uma Agencia.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyFuncionario($id){
        $user = User::findOrFail($id);
        $funcionario = $user->funcionarioUser()->get();
        $funcionario->delete();
        return redirect()->route('/')->with(['Mensagem' => 'Funcionario eliminada com sucesso', Response::HTTP_OK]);
    }

/**
 * -------------------------------------------------------------------------------------------------------------------------------------------------------
 * ---------------------------------------------------------------------fim Funcionarios -----------------------------------------------------------------
 * -------------------------------------------------------------------------------------------------------------------------------------------------------
 */  

}


