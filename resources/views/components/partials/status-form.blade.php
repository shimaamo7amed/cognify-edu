<div class="bg-white p-6 rounded-xl shadow border border-gray-100">
    <div class="flex items-center gap-2 mb-4">
        <x-heroicon-o-adjustments-horizontal class="w-5 h-5 text-primary-600" />
        <h3 class="text-base font-semibold">Status Management</h3>
    </div>

    <form wire:submit.prevent="updateStatus" class="space-y-4">
        <label class="block text-sm font-medium text-gray-700">Update Status</label>
        <select wire:model.defer="status" class="w-full rounded border-gray-300">
            @foreach(['new_request','under_review','approved','assigned','scheduled','completed','cancelled'] as $statusOption)
                <option value="{{ $statusOption }}">{{ ucfirst(str_replace('_', ' ', $statusOption)) }}</option>
            @endforeach
        </select>

        <x-filament::button type="submit" color="primary" size="sm">
            Update Status
        </x-filament::button>
    </form>
</div>
