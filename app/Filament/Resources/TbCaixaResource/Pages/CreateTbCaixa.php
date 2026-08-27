<?php

namespace App\Filament\Resources\TbCaixaResource\Pages;

use App\Filament\Resources\TbCaixaResource;
use App\Filament\Support\LookupReplication;
use App\Repositories\TbCaixaRepository;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTbCaixa extends CreateRecord
{
    protected static string $resource = TbCaixaResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $record = static::getModel()::create($data);

        LookupReplication::afterCreate($record, $data, TbCaixaRepository::class);

        return $record;
    }
}
