<?php

namespace App\Providers;

use App\Entities\TbClosing;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class UniquePeriodServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        
    
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('uniqueperiodduple', 
                    function($attribute, $value, $parameters, $validator)
        {
            
            $year = (string)(substr($parameters[0],-strlen($parameters[0]), 4));
            $id = (string)(substr($parameters[0],-(strlen($parameters[0])-4)));
            
            //create
            if(!$id){
                $result = (TbClosing::where($attribute, $value)
                                    ->where('year',  $year)
                                    ->count() > 0);
            //update
            }else{
                $result = (TbClosing::where($attribute, $value)
                                    ->where('year',  $year)
                                    ->where('id', '<>', $id)
                                    ->count() > 0);

            }
            
            if ($result)
            {
                return false;
            }
            return true;
        });

        
    }

    
}
