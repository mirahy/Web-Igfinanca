<?php

namespace App\Filament\Pages;

use App\Filament\Support\ResolvesFilialConnection;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SelectFilial extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationLabel = 'Selecionar Filial';

    protected static ?string $title = 'Selecionar Filial';

    protected static string $view = 'filament.pages.select-filial';

    public ?int $idtb_base = null;

    public function mount(): void
    {
        $this->idtb_base = ResolvesFilialConnection::currentBase()['id'] ?? null;
        $this->form->fill(['idtb_base' => $this->idtb_base]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('idtb_base')
                    ->label('Filial')
                    ->options(fn () => ResolvesFilialConnection::availableBasesFor(auth()->user())->pluck('name', 'id'))
                    ->required()
                    ->native(false),
            ]);
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        $base = ResolvesFilialConnection::availableBasesFor(auth()->user())
            ->firstWhere('id', $data['idtb_base']);

        if (! $base) {
            Notification::make()
                ->title('Filial inválida')
                ->body('Você não tem acesso a essa filial.')
                ->danger()
                ->send();

            return;
        }

        ResolvesFilialConnection::setCurrentBase($base);

        Notification::make()
            ->title('Filial selecionada: ' . $base->name)
            ->success()
            ->send();

        $this->redirect(static::getUrl());
    }
}
