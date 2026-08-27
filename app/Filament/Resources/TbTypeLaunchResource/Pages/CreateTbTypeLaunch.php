<?php

namespace App\Filament\Resources\TbTypeLaunchResource\Pages;

use App\Filament\Resources\TbTypeLaunchResource;
use App\Filament\Support\LookupReplication;
use App\Repositories\TbTypeLaunchRepository;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTbTypeLaunch extends CreateRecord
{
    protected static string $resource = TbTypeLaunchResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $record = static::getModel()::create($data);

        LookupReplication::afterCreate($record, $data, TbTypeLaunchRepository::class);

        return $record;
    }
}
