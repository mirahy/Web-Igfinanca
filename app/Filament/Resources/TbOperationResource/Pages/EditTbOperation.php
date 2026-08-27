<?php

namespace App\Filament\Resources\TbOperationResource\Pages;

use App\Filament\Resources\TbOperationResource;
use App\Filament\Support\LookupReplication;
use App\Repositories\TbOperationRepository;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTbOperation extends EditRecord
{
    protected static string $resource = TbOperationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(fn (Model $record) => LookupReplication::beforeDelete($record, TbOperationRepository::class)),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        LookupReplication::afterUpdate($record, $data, TbOperationRepository::class);

        return $record;
    }
}
