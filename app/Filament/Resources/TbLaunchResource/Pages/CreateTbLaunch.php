<?php

namespace App\Filament\Resources\TbLaunchResource\Pages;

use App\Entities\TbCadUser;
use App\Filament\Resources\TbLaunchResource;
use App\Filament\Support\ResolvesFilialConnection;
use App\Services\TbLaunchService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;

class CreateTbLaunch extends CreateRecord
{
    protected static string $resource = TbLaunchResource::class;

    public function mount(): void
    {
        ResolvesFilialConnection::assertConnected();

        parent::mount();
    }

    /**
     * Delega para TbLaunchService::store(), que já cuida de validação de
     * negócio, bloqueio de período fechado e replicação matriz<->filial via
     * id_filial/id_mtz — não reimplementar nada disso aqui.
     */
    protected function handleRecordCreation(array $data): Model
    {
        ResolvesFilialConnection::assertConnected();

        // O validator legado exige "name" (usado historicamente como campo
        // de autocomplete do lançador) mesmo sem persistir — sintetiza a
        // partir do usuário escolhido no Select, já que o form usa
        // id_user diretamente.
        $user = TbCadUser::find($data['id_user']);
        $data['name'] = $user?->name;
        $data['idtb_base'] = ResolvesFilialConnection::currentBase()['id'];
        $data['status'] = 0;

        $result = ResolvesFilialConnection::withLegacyBaseSessionBridge(
            fn () => app(TbLaunchService::class)->store($data)
        );

        if (! $result['success']) {
            Notification::make()
                ->title('Não foi possível salvar o lançamento')
                ->body(is_array($result['messages']) ? implode(' ', $result['messages']) : $result['messages'])
                ->danger()
                ->send();

            throw new Halt();
        }

        return $result['data'];
    }
}
