<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Validators\TbCadUserValidator;
use App\Repositories\TbCadUserRepository;
use App\Services\LoginService;
use Illuminate\Support\Facades\DB;
use App\Services\TbLaunchService;
use Exception;
use Auth;
use App\Entities\TbCadUser;
use App\Entities\TbClosing;
Use App\Http\Controllers\Controller;

class Login extends Controller
{

  Private $repository;
  Private $validator;
  Private $service;
  Private $serviceLaunch;

  public function __construct(TbCadUserRepository $repository, TbCadUserValidator $validator, LoginService $service, TbLaunchService $serviceLaunch)
  {
      $this->repository     = $repository;
      $this->validator      = $validator;
      $this->service        = $service;
      $this->serviceLaunch  = $serviceLaunch;

      //$this->middleware('permission:launch-create|launch-edit|launch-delete|launch-list', ['only' => ['index']]);
      
  }

  // função para login do usuário
  public function auth(Request $request)
  {

    //dd($request);

    $json  = array();
    $json["status"] = 1;
    $json["error_list"] = array();
  
    $request = $this->service->auth($request);
  
    session([
       'success'        =>  $request['success'],
       'messages'       =>  $request['messages'],
       'user'           =>  $request['data'],
       'base'           =>  $request['base'],
       'db'             =>  $request['db'],
       'id_base'        =>  $request['id_base'],
       'name_base'      =>  $request['name_base'],
       'token'          =>  $request['token'],
    ]);
    
    if(!$request['success']){
      $json["status"] = 0;
        foreach($request['messages'] as $msg){
            $json["error_list"]["#message"] = $msg; 
        } 
            $json["error_list"]["#email"] = "";
          
            $json["error_list"]["#password"] = ""; 
      } else{
        $json["data"] = $request;
      }     
  
      return json_encode($json);
  }


  // função para logout do usuário
  public function logout(Request $request)
  {
    
    if(Auth::check())
    {
      Auth::logout();
      // Revoke all tokens...
      auth()->user()->tokens()->delete();
      $request->session()->flush();

      return json_encode("Saiu!!!");

    }

    return json_encode("!!!");
    
  }

 
}
