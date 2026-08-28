<x-filament::dropdown.list>
    <div class="fi-dropdown-list-item px-3 py-2">
        <label class="fi-fo-field-wrp-label mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">
            {{ __('Filial') }}
        </label>

        <x-filament::input.wrapper>
            <x-filament::input.select wire:model.live="idtbBase">
                @foreach ($this->bases as $base)
                    <option value="{{ $base->id }}">{{ $base->name }}</option>
                @endforeach
            </x-filament::input.select>
        </x-filament::input.wrapper>
    </div>
</x-filament::dropdown.list>
