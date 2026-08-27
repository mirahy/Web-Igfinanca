<?php

namespace App\Filament\Resources\TbLaunchResource\Pages;

use App\Entities\TbCadUser;
use App\Filament\Resources\TbLaunchResource;
use App\Filament\Support\ResolvesFilialConnection;
use App\Services\TbLaunchService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;

class EditTbLaunch extends EditRecord
{
    protected static string $resource = TbLaunchResource::class;

    public function mount(int|string $record): void
    {
        ResolvesFilialConnection::assertConnected();

        parent::mount($record);
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        ResolvesFilialConnection::assertConnected();

        $user = TbCadUser::find($data['id_user']);
        $data['name'] = $user?->name;
        $data['id'] = $record->getKey();

        $result = app(TbLaunchService::class)->update($data);

        ResolvesFilialConnection::assertConnected();

        if (! $result['success']) {
            Notification::make()
                ->title('Não foi possível atualizar o lançamento')
                ->body(is_array($result['messages']) ? implode(' ', $result['messages']) : $result['messages'])
                ->danger()
                ->send();

            throw new Halt();
        }

        return $record->fresh();
    }
}
