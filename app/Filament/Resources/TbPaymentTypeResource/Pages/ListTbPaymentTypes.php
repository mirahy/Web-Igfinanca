<?php

namespace App\Filament\Resources\TbPaymentTypeResource\Pages;

use App\Filament\Resources\TbPaymentTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTbPaymentTypes extends ListRecords
{
    protected static string $resource = TbPaymentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
