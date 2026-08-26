<?php

namespace App\Filament\Resources\TbOperationResource\Pages;

use App\Filament\Resources\TbOperationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTbOperation extends EditRecord
{
    protected static string $resource = TbOperationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
