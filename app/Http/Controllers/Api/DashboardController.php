<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\TbLaunchService;
use Exception;
use Auth;
Use App\Http\Controllers\Controller;

class DashboardController extends Controller
{

  Private $repository;
  Private $validator;
  Private $service;
  Private $serviceLaunch;

  public function __construct( TbLaunchService $serviceLaunch)
  {
      $this->serviceLaunch  = $serviceLaunch;
      
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

}
