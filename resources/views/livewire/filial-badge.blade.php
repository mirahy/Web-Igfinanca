<div>
    @if ($base)
        <span
            @class([
                'fi-badge flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1',
                'bg-primary-50 text-primary-700 ring-primary-600/10 dark:bg-primary-400/10 dark:text-primary-400 dark:ring-primary-400/30',
            ])
        >
            <x-heroicon-m-building-office-2 class="h-4 w-4" />
            {{ $base['name'] }}
        </span>
    @endif
</div>
