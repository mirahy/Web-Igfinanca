<?php

namespace App\Http\Middleware;

use Closure;

class RouteAccessesFilial
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

        if ($request->session()->has('base')) //veirifica se o item base existe e não é null e se esta logado na matriz
        {
            $base = session()->get('base');
            $base = $base[0]['sigla'];

            if($base == 'adb_mtz')//veirifica se esta logado na matriz
            {
                $request->session()->put('alert-danger', 'Acesso não permitido. Contacte o administrador e verifique seus acessos!!!');

                return redirect('/dashboard');

            }
        }
        return $next($request);
    }
}
