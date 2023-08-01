<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    //
    public function createLogin(){
        return view('');
    }
    public function login(Request $request)
        {
          $userLog = User::all()->where('email', '=', $request->input('email'))->first();
          if($userLog){
            if($userLog->tipo_user == 'Agente'){
                $credenciais = [
                    'username' => $request->input('username'),
                    'password' => $request->input('password')
                ];
                if(Auth::attempt($credenciais)){
                    /*$user = Auth::user();
                    session(['user' => $user]);
                    $login['success'] = true;
                    return response()->json($login);*/
                    $user = Auth::user();
                    $token = $user->createToken('SECRET_TOKEN');

                    $login['success'] = true;
                    $login['token'] = $token->plainTextToken;
                    $login['user'] = $user;

                    $login['success'] = true;
                    $login['mensagem'] = 'Logado com sucesso';
                    return response()->json($login);

                }
                $login['success'] = false;
                $login['mensagem'] = 'Dados invalidos';
                return response()->json($login);
            }
            else{
                $data = $userLog->created_at;
                if($data <= '2023-11-01 00:00:00'){
                    $credenciais = [
                        'username' => $request->input('username'),
                        'password' => $request->input('password')
                    ];
                    if(Auth::attempt($credenciais)){
                        /*$user = Auth::user();
                        session(['user' => $user]);
                        $login['success'] = true;
                        return response()->json($login);*/
                        $user = Auth::user();
                        $token = $user->createToken('SECRET_TOKEN');

                        $login['success'] = true;
                        $login['token'] = $token->plainTextToken;
                        $login['user'] = $user;

                        $login['success'] = true;
                        $login['mensagem'] = 'Logado com sucesso';
                        return response()->json($login);
                    }
                    $login['success'] = false;
                    $login['mensagem'] = 'Dados invalidos';
                    return response()->json($login);
                }
                
          }
         
        }
        $login['success'] = false;
        $login['mensagem'] = 'Dados invalidos';
        return response()->json($login);
    
    }

    public function logout(){
        Auth::logout();
        Session::forget('user');
        return redirect()->route('home');
    }
}
