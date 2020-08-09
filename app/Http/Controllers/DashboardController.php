<?php

namespace App\Http\Controllers;


use App\Entities\TbCadUser;
use App\Entities\TbLaunch;
use Illuminate\Http\Request;
use App\Validators\TbCadUserValidator;
use App\Repositories\TbCadUserRepository;
use App\Services\LoginService;
use Exception;
use Auth;

class DashboardController extends Controller
{

  Private $repository;
  Private $validator;
  Private $service;

  public function __construct(TbCadUserRepository $repository, TbCadUserValidator $validator, LoginService $service)
  {
      $this->repository = $repository;
      $this->validator  = $validator;
      $this->service = $service;
  }

  public function index()
  {
    //Dízimos
    $entradas  = TbLaunch::where([['status', 1],['idtb_operation', 1],['idtb_type_launch', 1],['idtb_caixa', 1]])->sum('value');
    $saidas    = TbLaunch::where([['status', 1],['idtb_operation', 2],['idtb_caixa', 1]])->whereIn('idtb_type_launch',[3,4])->sum('value');

    $entradas_o  = TbLaunch::where([['status', 1],['idtb_operation', 1],['idtb_type_launch', 2],['idtb_caixa', 2]])->sum('value');
    $saidas_o    = TbLaunch::where([['status', 1],['idtb_operation', 2],['idtb_caixa', 2]])->whereIn('idtb_type_launch',[3,4])->sum('value');
    

    return view('dashboard.dashboard', [

      'pend'    => TbLaunch::where([['status', 0],['idtb_caixa', 1]])->count(),
      'entries' => $entradas,
      'exits'   => $saidas,
      'balance' => $entradas - $saidas,

      'pend_o'    => TbLaunch::where([['status', 0],['idtb_caixa', 2]])->count(),
      'entries_o' => $entradas_o,
      'exits_o'   => $saidas_o,
      'balance_o' => $entradas_o - $saidas_o,
      
    ]);

  }


  public function auth(Request $request)
  {

    $json  = array();
    $json["status"] = 1;
    $json["error_list"] = array();
    
    $request = $this->service->auth($request);
    
    session()->flash('success', [
       'success'        =>  $request['success'],
       'messages'       =>  $request['messages'],
  
    ]);

    if(!$request['success']){
      $json["status"] = 0;
        foreach($request['messages'] as $msg){
            $json["error_list"]["#message"] = $msg; 
        } 
            $json["error_list"]["#email"] = "";
          
            $json["error_list"]["#password"] = ""; 
      }      


      echo json_encode($json);
  }


  public function logout()
  {

    if(Auth::check())
    {
      Auth::logout();


    }


      return redirect()->route('user.login');
    }





}
