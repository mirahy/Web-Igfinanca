<?php

namespace App\Filament\Support;

use App\Entities\TbBase;
use App\Http\Controllers\ConnectDbController;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Collection;

/**
 * Troca de conexão de banco por filial dentro do painel Filament.
 *
 * As rotas do Filament não passam por routes/web.php, então os middlewares
 * de troca de base do sistema legado (reconnect, accesses_filial) não se
 * aplicam aqui. Além disso, o middleware do próprio painel não é suficiente
 * sozinho: ações do Livewire (criar, salvar, etc.) rodam como requisições
 * PHP novas que não necessariamente re-executam o middleware da forma
 * esperada. Por isso a conexão precisa ser reafirmada explicitamente aqui,
 * a cada chamada que efetivamente toca o banco (mount, getEloquentQuery,
 * handleRecordCreation, handleRecordUpdate, etc.) — mesmo padrão defensivo
 * que App\Services\TbLaunchService já usa hoje.
 *
 * Usa uma chave de sessão própria do painel ('filament.base'), separada de
 * session('base') do sistema legado, para não acoplar os dois: trocar a
 * filial aqui não deve mudar silenciosamente o comportamento do sistema
 * antigo numa aba diferente do mesmo navegador.
 */
class ResolvesFilialConnection
{
    public static function currentBase(): ?array
    {
        return session('filament.base');
    }

    public static function setCurrentBase(TbBase $base): void
    {
        session(['filament.base' => [
            'id' => $base->id,
            'sigla' => $base->sigla,
            'name' => $base->name,
        ]]);
    }

    public static function clearCurrentBase(): void
    {
        session()->forget('filament.base');
    }

    /**
     * Garante que a conexão do banco está na filial selecionada na sessão.
     * Se nenhuma filial foi selecionada ainda, notifica e interrompe a
     * execução (Halt) — o middleware EnsureFilialSelected cuida de
     * redirecionar antes de chegar aqui na maioria dos casos, isso é
     * só a garantia de última linha.
     */
    public static function assertConnected(): void
    {
        $base = static::currentBase();

        if (! $base) {
            Notification::make()
                ->title('Selecione uma filial')
                ->body('Escolha uma filial antes de continuar.')
                ->warning()
                ->send();

            throw new Halt();
        }

        app(ConnectDbController::class)->connectBases($base['sigla']);
    }

    /**
     * Filiais que o usuário autenticado pode selecionar. Administradores
     * (role Admin) veem todas; os demais só as atribuídas via
     * TbCadUser::bases() (tabela user_has_base). Sempre resolvido contra a
     * matriz, já que TbBase e user_has_base são geridos só lá.
     */
    public static function availableBasesFor($user): Collection
    {
        app(ConnectDbController::class)->connectMatriz();

        if ($user->hasRole('Admin')) {
            return TbBase::query()->orderBy('name')->get();
        }

        return $user->bases()->orderBy('name')->get();
    }

    /**
     * App\Services\TbLaunchService::store() termina revertendo a conexão pra
     * filial através de ConnectDbController::connectBase(), que lê
     * session('base') — a chave do sistema legado, não a nossa
     * ('filament.base'). Numa sessão que só passou pelo painel Filament essa
     * chave nunca existe, então connectBase() vira um no-op e o passo do
     * store() que atualiza id_mtz na filial acaba rodando na conexão errada
     * (matriz), correndo o risco de sobrescrever um registro de outra
     * filial/matriz que por acaso tenha o mesmo id.
     *
     * Em vez de reimplementar TbLaunchService (mantido intocado de propósito
     * — já testado em produção), preenchemos session('base') no formato que
     * ConnectDbController espera só durante a chamada, e desfazemos logo
     * depois — a sessão é persistida no fim do request, então nada disso
     * escapa pra uma aba aberta no sistema legado.
     */
    public static function withLegacyBaseSessionBridge(callable $callback): mixed
    {
        $base = static::currentBase();
        $hadPrevious = session()->has('base');
        $previous = session('base');

        session(['base' => [['sigla' => $base['sigla']]]]);

        try {
            return $callback();
        } finally {
            if ($hadPrevious) {
                session(['base' => $previous]);
            } else {
                session()->forget('base');
            }

            static::assertConnected();
        }
    }
}
