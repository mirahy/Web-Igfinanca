<?php

namespace App\Http\Controllers;

use Auth;
use App\Entities\TbLaunch;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    Private $DashboardController;
  
    public function __construct(DashboardController $DashboardController)
    {
        $this->DashboardController = $DashboardController;
       
    }
  


    /**
    * method  to acess home page
    *========================================================================
    */
    public function homepage()
    {
      
      if(Auth::check()){
        return $this->DashboardController->index();

        }else{

            return view('user.login');
          }
    
      }
    


    /**
    * method  to acess login page
    *========================================================================
    */
    public function telalogin()
    {
      if(Auth::check()){
        return $this->DashboardController->index();

      }else{

          return view('user.login');
        }
    }


}
