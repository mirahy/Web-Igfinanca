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
     * A matriz usa sempre a sigla/conexão 'adb_mtz' — mesma convenção já
     * usada em App\Services\ReplicaDbService e nos middlewares legados de
     * rota (RouteAccessesMatriz/RouteAccessesFilial).
     */
    public static function currentBaseIsMatriz(): bool
    {
        return (static::currentBase()['sigla'] ?? null) === 'adb_mtz';
    }

    /**
     * Se o painel ainda não tem uma filial selecionada nesta sessão, tenta
     * herdar automaticamente a mesma base já escolhida no login do sistema
     * legado (session('id_base'), setada em App\Http\Controllers\Api\Login).
     * Só copia o id — resolve o registro correspondente contra a matriz e
     * confirma que o usuário tem acesso a essa base antes de aceitar, então
     * uma sessão legada inválida/de outro usuário nunca é usada às cegas.
     *
     * Não sobrescreve uma escolha já feita no painel: depois da primeira
     * seleção (automática ou manual), o Filament passa a ser independente
     * do sistema legado, como já documentado na classe.
     */
    public static function autoSelectFromLegacySession($user): void
    {
        if (static::currentBase()) {
            return;
        }

        $legacyBaseId = session('id_base');

        if (! $legacyBaseId) {
            return;
        }

        $match = static::availableBasesFor($user)->firstWhere('id', $legacyBaseId);

        if ($match) {
            static::setCurrentBase($match);
        }
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
     * Reconecta pra filial ativa antes do Livewire processar uma ação
     * (POST /livewire/update) que pertence ao TbLaunchResource.
     *
     * A rota /livewire/update é global — registrada pelo próprio pacote
     * Livewire fora do escopo do painel Filament, com só o middleware
     * 'web' padrão do Laravel (confirmado via `route:list`) — então
     * nenhum middleware do painel roda nela, nem ReconnectDbDefault nem
     * EnsureFilialSelected. Sem essa reconexão, o synthesizer nativo de
     * Model do Livewire reidrata a propriedade $record (buscando o
     * registro no banco) usando qualquer conexão que tenha sobrado de
     * antes — e como os ids de tb_launch só existem mesmo na filial, essa
     * busca falha e vira 404 (Laravel converte ModelNotFoundException não
     * capturada em NotFoundHttpException). Isso acontece antes até de
     * handleRecordUpdate() rodar, então precisa ser resolvido aqui.
     *
     * Registrado via Livewire::listen('request', ...) em
     * App\Providers\Filament\AdminPanelProvider::boot() — o hook 'request'
     * do próprio Livewire roda pra QUALQUER requisição de update
     * (independente de painel/rota), exatamente o gancho cedo o
     * suficiente que um middleware HTTP não consegue oferecer aqui.
     *
     * @param  array<int, array{snapshot?: string}>  $requestPayload
     */
    public static function reconnectForLivewireUpdateIfNeeded(array $requestPayload): void
    {
        if (! static::currentBase()) {
            return;
        }

        foreach ($requestPayload as $component) {
            $snapshot = json_decode($component['snapshot'] ?? '', associative: true);
            $path = $snapshot['memo']['path'] ?? '';

            if (str_contains($path, 'tb-launches')) {
                static::assertConnected();

                return;
            }
        }
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
