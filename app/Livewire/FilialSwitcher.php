<?php

namespace App\Livewire;

use App\Filament\Support\ResolvesFilialConnection;
use Livewire\Component;

/**
 * Dropdown de troca rápida de filial, dentro do menu do usuário do painel.
 * Ao trocar, recarrega a página inteira (não só o componente) — várias
 * partes do painel só reafirmam a conexão em pontos específicos do ciclo de
 * vida (ver ResolvesFilialConnection), então um simples "$refresh" do
 * Livewire poderia deixar a página atual com estado misto de duas filiais.
 */
class FilialSwitcher extends Component
{
    public ?int $idtbBase = null;

    public function mount(): void
    {
        $this->idtbBase = ResolvesFilialConnection::currentBase()['id'] ?? null;
    }

    public function getBasesProperty()
    {
        return ResolvesFilialConnection::availableBasesFor(auth()->user());
    }

    public function updatedIdtbBase(): void
    {
        $base = $this->bases->firstWhere('id', $this->idtbBase);

        if (! $base) {
            return;
        }

        ResolvesFilialConnection::setCurrentBase($base);

        $this->redirect(request()->header('Referer') ?: route('filament.admin.pages.dashboard'), navigate: false);
    }

    public function render()
    {
        return view('livewire.filial-switcher');
    }
}
