<?php

namespace App\Filament\Resources\TbPaymentTypeResource\Pages;

use App\Filament\Resources\TbPaymentTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTbPaymentType extends EditRecord
{
    protected static string $resource = TbPaymentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
