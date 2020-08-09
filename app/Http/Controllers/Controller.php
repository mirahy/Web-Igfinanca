<?php

namespace App\Http\Controllers;

use Auth;
use App\Entities\TbLaunch;
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
      
      if(Auth::check()){
        return view('dashboard.dashboard', [

          'pend'=> TbLaunch::where('status', 0)->count(),
          'entries'=> TbLaunch::where([['status', 1],['idtb_operation', 1]])->sum('value'),
          'exits'=> TbLaunch::where([['status', 1],['idtb_operation', 2]])->sum('value'),
          
          
          ]);

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
      return view('dashboard.dashboard', [

        'pend'=> TbLaunch::where('status', 0)->count(),
          'entries'=> TbLaunch::where([['status', 1],['idtb_operation', 1]])->sum('value'),
          'exits'=> TbLaunch::where([['status', 1],['idtb_operation', 2]])->sum('value'),
        
        
        ]);

      }else{

          return view('user.login');
        }
    }


}
