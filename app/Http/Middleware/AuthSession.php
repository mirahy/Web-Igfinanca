<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AuthSession
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
         /* Verifica se o valor da coluna/sessão "token_access" NÃO é compatível com o valor da sessão que criamos quando o usuário fez login*/  
         if (!(Auth::check() && session()->get('user'))){
            // Faz o logout do usuário
            Auth::logout();
            $request->session()->flush();
            // Redireciona o usuário para a página de login, com session "message"
            $request->session()->put('alert-danger', 'Sua sessão expirou!');

            return redirect()->route('user.login');
        }
        return $next($request);
    }
}
