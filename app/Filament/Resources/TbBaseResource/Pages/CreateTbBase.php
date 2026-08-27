<?php

namespace App\Filament\Resources\TbBaseResource\Pages;

use App\Filament\Resources\TbBaseResource;
use App\Filament\Support\LookupReplication;
use App\Repositories\TbBaseRepository;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTbBase extends CreateRecord
{
    protected static string $resource = TbBaseResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $record = static::getModel()::create($data);

        LookupReplication::afterCreate($record, $data, TbBaseRepository::class);

        return $record;
    }
}
