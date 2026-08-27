<?php

namespace App\Http\Middleware\Filament;

use App\Filament\Pages\SelectFilial;
use App\Filament\Support\ResolvesFilialConnection;
use Closure;
use Illuminate\Http\Request;

/**
 * Camada de UX: redireciona para a seleção de filial antes de entrar numa
 * tela que depende dela (hoje só TbLaunchResource). Não é a garantia de
 * correção — essa continua sendo ResolvesFilialConnection::assertConnected()
 * chamado explicitamente dentro dos hooks que tocam o banco (ver
 * app/Filament/Support/ResolvesFilialConnection.php).
 */
class EnsureFilialSelected
{
    public function handle(Request $request, Closure $next)
    {
        if (
            $request->routeIs('filament.admin.resources.tb-launches.*')
            && ! ResolvesFilialConnection::currentBase()
        ) {
            return redirect(SelectFilial::getUrl());
        }

        return $next($request);
    }
}
