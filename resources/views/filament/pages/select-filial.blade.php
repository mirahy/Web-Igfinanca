<x-filament-panels::page>
    <form wire:submit="submit">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="[
                \Filament\Actions\Action::make('submit')
                    ->label('Selecionar')
                    ->submit('submit'),
            ]"
        />
    </form>
</x-filament-panels::page>
