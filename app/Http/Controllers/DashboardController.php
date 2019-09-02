<?php

namespace App\Http\Controllers;


use App\Entities\TbCadUser;
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
    return view('dashboard.dashboard');
  }


  public function auth(Request $request)
  {

    $json  = array();
    $json["status"] = 1;
    $json["error_list"] = array();;
    
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
