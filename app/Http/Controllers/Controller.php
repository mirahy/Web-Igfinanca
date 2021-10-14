<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Repositories\TbBaseRepository;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $TbBaseRepository;

    public function __construct(TbBaseRepository $TbBaseRepository)
    {
        $this->TbBaseRepository     = $TbBaseRepository;

    }


    /**
    * method  to acess home page
    *========================================================================
    */
    public function homepage()
    {
      
      if(Auth::check()){
        return redirect('/dashboard');

        }else{

              $base_list    = $this->TbBaseRepository->selectBoxList();

              return view('user.login',[
                'base_list'    => $base_list,
            ]);
          }
    
      }
    


    /**
    * method  to acess login page
    *========================================================================
    */
    public function telalogin()
    {
      if(Auth::check()){
        return redirect('/dashboard');

      }else{

          $base_list    = $this->TbBaseRepository->selectBoxList();

            return view('user.login',[
              'base_list'    => $base_list,
          ]);
        }
    }


}
