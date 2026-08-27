<?php

namespace App\Filament\Resources\TbLaunchResource\Pages;

use App\Filament\Resources\TbLaunchResource;
use App\Filament\Support\ResolvesFilialConnection;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTbLaunches extends ListRecords
{
    protected static string $resource = TbLaunchResource::class;

    public function mount(): void
    {
        ResolvesFilialConnection::assertConnected();

        parent::mount();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
