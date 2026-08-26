<?php

namespace App\Filament\Resources\TbCaixaResource\Pages;

use App\Filament\Resources\TbCaixaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTbCaixas extends ListRecords
{
    protected static string $resource = TbCaixaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
