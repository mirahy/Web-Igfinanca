<?php

namespace App\Filament\Resources\TbOperationResource\Pages;

use App\Filament\Resources\TbOperationResource;
use App\Filament\Support\LookupReplication;
use App\Repositories\TbOperationRepository;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTbOperation extends CreateRecord
{
    protected static string $resource = TbOperationResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $record = static::getModel()::create($data);

        LookupReplication::afterCreate($record, $data, TbOperationRepository::class);

        return $record;
    }
}
