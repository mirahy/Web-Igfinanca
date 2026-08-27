<?php

namespace App\Filament\Resources\TbPaymentTypeResource\Pages;

use App\Filament\Resources\TbPaymentTypeResource;
use App\Filament\Support\LookupReplication;
use App\Repositories\TbPaymentTypeRepository;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTbPaymentType extends CreateRecord
{
    protected static string $resource = TbPaymentTypeResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $record = static::getModel()::create($data);

        LookupReplication::afterCreate($record, $data, TbPaymentTypeRepository::class);

        return $record;
    }
}
