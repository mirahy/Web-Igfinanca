<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class PeriodServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('period', 
                    function($attribute, $value, $parameters, $validator)
        {
     
            $initPeriod = new \DateTime((string)(substr($parameters[0],-strlen($parameters[0]), 10)));
            $finalPeriod = new \DateTime((string)(substr($parameters[0],-(strlen($parameters[0])-10))));
            $value = new \DateTime($value);

            if ($value >= $initPeriod & $value <= $finalPeriod)
            {
                return true;
            }
            return false;
        });
    }
}
