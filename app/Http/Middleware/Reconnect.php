<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;


class Reconnect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ($request->session()->has('base')) //veirifica se o item base existe e não é null
        {
            $base = session()->get('base');
            $base = $base[0]['sigla'];

            if (DB::connection()->getDatabaseName() != $base) //verifica se a conexão ja existe
            {

                Config::set('database.default', $base); //atribuir a conexão padrão  

                // Conecta no banco
                DB::reconnect($base);
            }
        }

        return $next($request);
    }
}
