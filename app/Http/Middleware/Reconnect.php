<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Entities\TbBase;


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
        $base = session()->get('base');
        $base = $base[0]['sigla'];
        
        // Apaga a conexão db, forçando o Laravel a voltar suas configurações de conexão para o padrão.
        DB::purge($base);
        

        Config::set("database.connections.".$base, [
            "driver" => "mysql",
            "host" => env('DB_HOST', '45.34.12.248'),
            "database" => $base,
            "username" => env('DB_USERNAME_VLA', 'forge'),
            "password" => env('DB_PASSWORD_VLA', ''),
        ]);

        Config::set('database.default',$base); //atribuir a conexão padrão  
        
        // Conecta no banco
        DB::reconnect($base);
       
        return $next($request);
    }
}
