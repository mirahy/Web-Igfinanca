<?php

namespace App\Filament\Resources\TbCaixaResource\Pages;

use App\Filament\Resources\TbCaixaResource;
use App\Filament\Support\LookupReplication;
use App\Repositories\TbCaixaRepository;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTbCaixa extends EditRecord
{
    protected static string $resource = TbCaixaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(fn (Model $record) => LookupReplication::beforeDelete($record, TbCaixaRepository::class)),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        LookupReplication::afterUpdate($record, $data, TbCaixaRepository::class);

        return $record;
    }
}
