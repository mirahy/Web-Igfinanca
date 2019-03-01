<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
    * method  to acess home page
    *========================================================================
    */
    public function homepage()
    {
      if(Auth::check())
        return view('dashboard');

        return view('user.login');
    }


    /**
    * method  to acess login page
    *========================================================================
    */
    public function telalogin()
    {
      if(Auth::check())
      return view('dashboard');

      return view('user.login');
    }


}
