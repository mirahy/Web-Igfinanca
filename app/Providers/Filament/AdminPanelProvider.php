<?php

namespace App\Providers\Filament;

use App\Filament\Support\ResolvesFilialConnection;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Livewire\Livewire;

class AdminPanelProvider extends PanelProvider
{
    public function boot(): void
    {
        // POST /livewire/update é uma rota global (fora do escopo do
        // painel, só com o middleware 'web'), então a reconexão pra
        // filial de TbLaunchResource não pode viver num middleware do
        // Panel — precisa ser um hook do próprio Livewire. Ver
        // App\Filament\Support\ResolvesFilialConnection::reconnectForLivewireUpdateIfNeeded().
        Livewire::listen('request', function (array $requestPayload) {
            ResolvesFilialConnection::reconnectForLivewireUpdateIfNeeded($requestPayload);
        });
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->authGuard('web')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                // As rotas do Filament não passam por routes/web.php, então os
                // middlewares de troca de base (reconnect, accesses_filial) não se
                // aplicam aqui automaticamente. Os Resources de configuração
                // (TbPaymentType, TbCaixa, TbOperation, TbTypeLaunch, TbBase,
                // Roles, Permissions) são geridos só contra a matriz, então basta
                // forçar essa conexão a cada request. TbLaunchResource é por
                // filial — usa o seletor de base próprio do painel
                // (App\Filament\Pages\SelectFilial) e reafirma a conexão
                // explicitamente em cada hook que toca o banco (ver
                // App\Filament\Support\ResolvesFilialConnection), já que nem
                // middleware persistente garante reexecução nas ações do Livewire.
                \App\Http\Middleware\ReconnectDbDefault::class,
            ])
            ->middleware([
                \App\Http\Middleware\Filament\EnsureFilialSelected::class,
            ], isPersistent: true)
            ->authMiddleware([
                Authenticate::class,
            ])
            // Badge com o nome da filial ativa, antes do menu do usuário na
            // topbar, e o seletor de troca rápida dentro desse menu — ver
            // App\Livewire\FilialBadge / FilialSwitcher.
            ->renderHook(
                PanelsRenderHook::GLOBAL_SEARCH_AFTER,
                fn (): string => Blade::render('@livewire(\'filial-badge\')'),
            )
            ->renderHook(
                PanelsRenderHook::USER_MENU_PROFILE_AFTER,
                fn (): string => Blade::render('@livewire(\'filial-switcher\')'),
            );
    }
}
