<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReconnectDbDefault
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
        // Apaga a conexão db, forçando o Laravel a voltar suas configurações de conexão para o padrão.
        DB::purge('adb_mtz');

        Config::set("database.connections.adb_mtz", [
            "driver" => "mysql",
            "host" => env('DB_HOST', '45.34.12.248'),
            "database" => "adb_mtz",
            "username" => env('DB_USERNAME', 'forge'),
            "password" => env('DB_PASSWORD', ''),
        ]);

        Config::set('database.default','adb_mtz'); //atribuir a conexão padrão
        
        
        // Conecta no banco
        DB::reconnect('adb_mtz');
        
        // // Testa a nova conexão
        // Schema::connection('adb_vla')->getConnection()->reconnect();
        return $next($request);
    }
}
