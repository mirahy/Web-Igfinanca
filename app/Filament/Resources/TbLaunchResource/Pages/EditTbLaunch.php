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

    /**
     * Editar (de fato salvar) só é permitido a partir da própria filial —
     * a página em si continua acessível com a matriz ativa (o form fica
     * somente leitura, ver TbLaunchResource::form()), pra permitir consultar
     * o lançamento de qualquer base sem precisar trocar de filial. Por isso
     * o botão "Salvar" some, mas "Cancelar" continua disponível.
     */
    protected function getFormActions(): array
    {
        if (ResolvesFilialConnection::currentBaseIsMatriz()) {
            return [$this->getCancelFormAction()];
        }

        return parent::getFormActions();
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        ResolvesFilialConnection::assertConnected();

        // Reforço além do form somente-leitura/botão oculto: TbLaunchService::update()
        // parte do pressuposto de que a conexão ativa é a filial (usa id_mtz
        // do registro pra achar o espelho na matriz) — quebraria com "No
        // query results for model" se chamado com a matriz ativa.
        if (ResolvesFilialConnection::currentBaseIsMatriz()) {
            Notification::make()
                ->title('Edição só pode ser feita a partir da base filial')
                ->danger()
                ->send();

            throw new Halt();
        }

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
