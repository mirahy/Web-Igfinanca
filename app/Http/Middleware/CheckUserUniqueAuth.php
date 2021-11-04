<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckUserUniqueAuth
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
                    // Faz o logout do usuário
                    Auth::logout();
                    $request->session()->flush();
                    // Redireciona o usuário para a página de login, com session "message"
                    $request->session()->put('alert-danger', 'A sessão deste usuário está ativa em outro local!');

                    return redirect()->route('user.login');
            }
                
            
        }
        
    
        // Permite o acesso, continua a requisição
        return $next($request);
    }
}
