<?php

namespace App\Filament\Resources\TbOperationResource\Pages;

use App\Filament\Resources\TbOperationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTbOperations extends ListRecords
{
    protected static string $resource = TbOperationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
