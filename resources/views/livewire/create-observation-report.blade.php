<div class="flex justify-end gap-4 mt-6">
    <x-filament::button wire:click="submit(false)" color="gray">
        Save as Draft
    </x-filament::button>

    @if ($progress === 100)
        <x-filament::button wire:click="submit(true)" color="primary">
            Finalize Report
        </x-filament::button>
    @endif
</div>
