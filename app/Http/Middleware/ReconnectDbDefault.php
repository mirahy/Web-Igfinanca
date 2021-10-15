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
        
        Config::set('database.default','adb_mtz'); //atribuir a conexão padrão
          
        // Conecta no banco
        DB::reconnect('adb_mtz');
        
        return $next($request);
    }
}
