<?php

namespace App\Http\Controllers;

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

class DashboardController extends Controller
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

    $json  = array();
    $json["status"] = 1;
    $json["error_list"] = array();
 
    
    $request = $this->service->auth($request);
    
    if($request['success'])
    session([
       'success'        =>  $request['success'],
       'messages'       =>  $request['messages'],
       'user'           =>  $request['data'],
       'base'           =>  $request['base'],
       'db'             =>  $request['db'],
       'id_base'        =>  $request['id_base'],
       'name_base'      =>  $request['name_base'],
    ]);
    
    if(!$request['success']){
      $json["status"] = 0;
        foreach($request['messages'] as $msg){
            $json["error_list"]["#message"] = $msg; 
        } 
            $json["error_list"]["#email"] = "";
          
            $json["error_list"]["#password"] = ""; 
      }      

      
      return json_encode($json);
  }

  // função para logout do usuário
  public function logout(Request $request)
  {
    
    if(Auth::check())
    {
      Auth::logout();
      $request->session()->flush();

    }

      return redirect()->route('user.login');
  }

  //funcçao de retorno de valores dos laçamentos para a view dashboard
  public function sum(Request $request)
  {
    $value = $this->serviceLaunch->sum($request);

    echo json_encode($value);

  }

   //funçao de retorno de valores para saldo inicial do período(para datas anteriores a 01/08/2021 retorna zero)
   public function saldo(Request $request)
   {
     $value = $this->serviceLaunch->saldo($request);
    
     echo json_encode($value);
 
   }

  //funcçao de retorno de pendencias dos laçamentos para a view dashboard
  public function pend(Request $request)
  {
    $value = $this->serviceLaunch->pend($request);

    echo json_encode($value);

  }

  //função redirecionamento para a view dashboard
  public function index(Request $request)
  {
    
    $periodo = TbClosing::where('status', 1)->get();
    return view('dashboard.dashboard', [
                'pend'      => 'Atualizando...',
                'entries'   => 'Calculando...',
                'exits'     => 'Calculando...',
                'balance'   => 'Calculando...',
                'pend_o'    => 'Atualizando...',
                'entries_o' => 'Calculando...',
                'exits_o'   => 'Calculando...',
                'balance_o' => 'Calculando...',
                'periodo'   => $periodo,
      
    ]);

  }





}
