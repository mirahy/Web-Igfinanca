<?php

namespace App\Livewire;

use App\Filament\Support\ResolvesFilialConnection;
use Livewire\Component;

/**
 * Badge sempre visível na topbar do painel (antes do menu do usuário) com
 * o nome da filial ativa. Só leitura — a troca em si é feita pelo
 * App\Livewire\FilialSwitcher, dentro do menu do usuário.
 */
class FilialBadge extends Component
{
    public function render()
    {
        return view('livewire.filial-badge', [
            'base' => ResolvesFilialConnection::currentBase(),
        ]);
    }
}
