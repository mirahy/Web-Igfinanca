<?php

namespace App\Filament\Resources\TbCaixaResource\Pages;

use App\Filament\Resources\TbCaixaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTbCaixa extends EditRecord
{
    protected static string $resource = TbCaixaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
