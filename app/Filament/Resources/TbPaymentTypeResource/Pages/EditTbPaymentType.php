<?php

namespace App\Filament\Resources\TbPaymentTypeResource\Pages;

use App\Filament\Resources\TbPaymentTypeResource;
use App\Filament\Support\LookupReplication;
use App\Repositories\TbPaymentTypeRepository;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTbPaymentType extends EditRecord
{
    protected static string $resource = TbPaymentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(fn (Model $record) => LookupReplication::beforeDelete($record, TbPaymentTypeRepository::class)),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        LookupReplication::afterUpdate($record, $data, TbPaymentTypeRepository::class);

        return $record;
    }
}
