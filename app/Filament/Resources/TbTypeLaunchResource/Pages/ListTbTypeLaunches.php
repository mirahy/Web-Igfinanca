<?php

namespace App\Filament\Resources\TbTypeLaunchResource\Pages;

use App\Filament\Resources\TbTypeLaunchResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTbTypeLaunches extends ListRecords
{
    protected static string $resource = TbTypeLaunchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
