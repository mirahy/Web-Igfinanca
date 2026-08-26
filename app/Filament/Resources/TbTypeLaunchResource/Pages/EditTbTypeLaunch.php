<?php

namespace App\Filament\Resources\TbTypeLaunchResource\Pages;

use App\Filament\Resources\TbTypeLaunchResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTbTypeLaunch extends EditRecord
{
    protected static string $resource = TbTypeLaunchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
