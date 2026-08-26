<?php

namespace App\Filament\Resources\TbBaseResource\Pages;

use App\Filament\Resources\TbBaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTbBase extends EditRecord
{
    protected static string $resource = TbBaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
