<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Laravel\Sanctum\HasApiTokens;


class CheckUserUniqueAuthAPI
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
        
        //Verifica se existe "access_token" na sessão
        if(session()->get('access_token')){

            /* Verifica se o valor da coluna/sessão "token_access" NÃO é compatível com o valor da sessão que criamos quando o usuário fez login*/  
            if (auth()->user()->token_access != session()->get('access_token')){
                    // Revoke all tokens...
                    auth()->user()->tokens()->delete();

                    $request->session()->flush();
                    
                    // Redireciona o usuário para a página de login, com session "message"
                    // return $request->session()->put('alert-danger', 'A sessão deste usuário está ativa em outro local!');
                    // return json_encode(['alert-danger' =>["A sessão deste usuário está ativa em outro local"]]);

            }
                
            
        }
        
        // Permite o acesso, continua a requisição
        return $next($request);
    }
}
