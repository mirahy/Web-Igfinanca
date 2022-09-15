<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
Use App\Http\Controllers\Controller;
use Laravel\Sanctum\HasApiTokens;
use App\Services\LoginService;

class Login extends Controller
{

  Private $service;

  public function __construct(LoginService $service)
  {
    $this->service  = $service;

  }

  // função para login do usuário
  public function auth(Request $request)
  {
    $email = $request->get('email');
    //realizando login
    $request = $this->service->auth($request);
    //gerando token
    if($request['success']){

     //gerar token
      $token = auth()->user()->createToken($email);

      //inserir dados na seção
      session([
        'success'        =>  $request['success'],
        'messages'       =>  $request['messages'],
        'user'           =>  $request['data'],
        'base'           =>  $request['base'],
        'db'             =>  $request['db'],
        'id_base'        =>  $request['id_base'],
        'name_base'      =>  $request['name_base'],
        'plainTextToken' =>  $token->plainTextToken
     ]);

    }else{
      return json_encode(['data' =>["Email e/ou senha inválidos"]]);
    }
  
    return json_encode(['data' =>['token' => $token->plainTextToken]]);

  }


  // função para logout do usuário
  public function logout(Request $request)
  { 
      // Revoke all tokens...
      auth()->user()->tokens()->delete();

      $request->session()->flush();
    
  }

 
}
