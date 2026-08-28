<?php

namespace App\Http\Middleware\Filament;

use App\Filament\Pages\SelectFilial;
use App\Filament\Support\ResolvesFilialConnection;
use Closure;
use Illuminate\Http\Request;

/**
 * Roda em toda requisição do painel, depois de ReconnectDbDefault (que já
 * forçou a conexão padrão pra matriz). Faz duas coisas:
 *
 * 1. Tenta herdar automaticamente a filial já selecionada no sistema legado
 *    (ResolvesFilialConnection::autoSelectFromLegacySession()) se o painel
 *    ainda não tem nenhuma selecionada nesta sessão.
 * 2. Redireciona pra seleção manual de filial antes de entrar numa tela que
 *    depende dela (hoje só TbLaunchResource) quando não foi possível herdar
 *    nenhuma.
 *
 * Não cobre as ações do Livewire (POST /livewire/update) — essa rota é
 * global (registrada pelo próprio pacote fora do escopo do painel, com só
 * o middleware 'web' padrão do Laravel), então nenhum middleware do painel
 * roda nela. Ver App\Providers\Filament\AdminPanelProvider::boot() para
 * como a reconexão é garantida ali (hook Livewire::listen('request', ...),
 * não um middleware HTTP).
 */
class EnsureFilialSelected
{
    public function handle(Request $request, Closure $next)
    {
        if ($user = $request->user()) {
            ResolvesFilialConnection::autoSelectFromLegacySession($user);
        }

        if (
            $request->routeIs('filament.admin.resources.tb-launches.*')
            && ! ResolvesFilialConnection::currentBase()
        ) {
            return redirect(SelectFilial::getUrl());
        }

        return $next($request);
    }
}
