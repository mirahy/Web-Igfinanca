<?php

namespace App\Filament\Resources\TbBaseResource\Pages;

use App\Filament\Resources\TbBaseResource;
use App\Filament\Support\LookupReplication;
use App\Repositories\TbBaseRepository;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTbBase extends EditRecord
{
    protected static string $resource = TbBaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(fn (Model $record) => LookupReplication::beforeDelete($record, TbBaseRepository::class)),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        LookupReplication::afterUpdate($record, $data, TbBaseRepository::class);

        return $record;
    }
}
