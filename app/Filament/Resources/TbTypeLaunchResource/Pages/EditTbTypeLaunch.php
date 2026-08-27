<?php

namespace App\Filament\Resources\TbTypeLaunchResource\Pages;

use App\Filament\Resources\TbTypeLaunchResource;
use App\Filament\Support\LookupReplication;
use App\Repositories\TbTypeLaunchRepository;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTbTypeLaunch extends EditRecord
{
    protected static string $resource = TbTypeLaunchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(fn (Model $record) => LookupReplication::beforeDelete($record, TbTypeLaunchRepository::class)),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        LookupReplication::afterUpdate($record, $data, TbTypeLaunchRepository::class);

        return $record;
    }
}
