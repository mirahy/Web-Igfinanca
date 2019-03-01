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
    return view('dashboard');
  }


  public function auth(Request $request)
  {

    $request = $this->service->auth($request);

    session()->flash('success', [
       'success'   =>  $request['success'],
       'messages'  =>  $request['messages'],
       'error'    =>  $request['error'],

    ]);

    return $request['success'] ? view('dashboard') : view('user.login', [ 'error' =>  $request['error']]);


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
