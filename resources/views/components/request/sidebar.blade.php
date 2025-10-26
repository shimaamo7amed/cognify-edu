@props(['requestCase', 'acceptedEmployees'])

<div class="lg:col-span-4 space-y-6 lg:sticky lg:top-20">
    {{-- 🔹 Status Management --}}
    <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
        <div class="flex items-center gap-2 mb-4">
            <x-heroicon-o-adjustments-horizontal class="w-5 h-5 text-primary-600" />
            <h3 class="text-base font-semibold">Status Management</h3>
        </div>

        <form wire:submit.prevent="updateStatus">
            <label class="block text-sm font-medium text-gray-700">Update Status</label>
            <select wire:model="status" class="w-full rounded border-gray-300">
                @foreach(['new_request','under_review','approved','assigned','scheduled','completed','cancelled'] as $statusOption)
                    <option value="{{ $statusOption }}"
                         {{ $requestCase->status === $statusOption ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $statusOption)) }}
                    </option>
                @endforeach
            </select>

            <x-filament::button type="submit" color="primary" size="sm" class="mt-6">
                Update Status
            </x-filament::button>
        </form>
    </div>

    {{-- 🔹 Assign Shadow Teacher --}}
    <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
        <div class="flex items-center gap-2 mb-4">
            <x-heroicon-o-academic-cap class="w-5 h-5 text-primary-600" />
            <h3 class="text-base font-semibold">Assign Shadow Teacher</h3>
        </div>

        @if(auth()->user()->hasRole('teacher'))
            {{-- Show only teacher name for teachers --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Assigned Teacher</label>
                <div class="mt-1 p-3 bg-gray-50 rounded border border-gray-200">
                    <span class="text-gray-900 font-medium">
                        {{ $requestCase->employee->name.' '.$requestCase->employee->middle_name.' '.$requestCase->employee->last_name ?? 'Not assigned yet' }}
                    </span>
                </div>
            </div>
        @else
            {{-- Show select dropdown for non-teachers (admin, etc.) --}}
            <form wire:submit.prevent="assignTeacher">
                <label class="block text-sm font-medium text-gray-700">Select Teacher</label>
                <select wire:model="employee_id" class="w-full rounded border-gray-300">
                    <option value="">Select Teacher</option>
                    @foreach($acceptedEmployees as $employee)
                        <option value="{{ $employee->id }}"
                             {{ $requestCase->employee_id == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name.' '.$employee->middle_name.' '.$employee->last_name }}
                        </option>
                    @endforeach
                </select>

                <x-filament::button type="submit" color="primary" size="sm" class="mt-6">
                    Assign Teacher
                </x-filament::button>
            </form>
        @endif
    </div>

    {{-- 🔹 Admin Notes --}}
    <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
        <div class="flex items-center gap-2 mb-4">
            <x-heroicon-o-document-text class="w-5 h-5 text-primary-600" />
            <h3 class="text-base font-semibold">Admin Notes</h3>
        </div>

        {{-- Existing Notes --}}
        <div class="space-y-4 text-sm text-gray-800">
            @forelse ($this->notes as $note)
                <div class="border rounded-lg p-3 bg-gray-50">
                    <div class="flex justify-between items-center mb-1">
                        <span class="font-semibold">{{ $note->author->name ?? 'Unknown' }}</span>
                        <span class="text-xs text-gray-500">{{ $note->created_at->format('F d, Y \a\t h:i A') }}</span>
                    </div>
                    <p class="text-gray-700">{{ $note->note }}</p>
                    <p class="text-xs text-gray-500 italic">{{ class_basename($note->author_type) }}</p>
                </div>
            @empty
                <p class="text-gray-500 italic">No notes yet.</p>
            @endforelse
        </div>

        {{-- Add New Note --}}
        <form wire:submit.prevent="addNote" class="mt-4 space-y-2">
            <textarea wire:model.defer="noteText" rows="3"
                class="w-full rounded border border-gray-300 p-2 text-sm"
                placeholder="Write your note here..." required></textarea>

            <x-filament::button type="submit" color="primary" size="sm">Add Note</x-filament::button>
        </form>
    </div>
</div>