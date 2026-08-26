<?php

namespace App\Filament\Resources\TbBaseResource\Pages;

use App\Filament\Resources\TbBaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTbBases extends ListRecords
{
    protected static string $resource = TbBaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
