<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class PassValidateProvider extends ServiceProvider
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
        Validator::extend('pass_validadte', 
                    function($attribute, $value, $parameters, $validator)
        {  

            if ($parameters[0] == $parameters[1])
            {

                return true;
            }

            return false;
        });
    }
}
