<x-filament::page>
    <form wire:submit.prevent="submit" class="space-y-6 max-w-md mx-auto">
        {{ $this->form }}

        <x-filament::button type="submit">
            Update Password
        </x-filament::button>

        @if (session()->has('success'))
            <div class="text-green-600 font-medium">
                {{ session('success') }}
            </div>
        @endif
    </form>
</x-filament::page>
