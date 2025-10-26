<x-filament-panels::page>

    <div class="bg-white rounded-xl shadow p-6 mb-6 border-b flex justify-between items-start">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Daily Report</h2>
            <p class="text-sm text-gray-500 mt-1">Progress: <span class="text-blue-600 font-bold">{{ $progress }}%</span></p>
            <p class="text-sm text-gray-500 mt-1">Status: 
                <span class="font-semibold capitalize text-gray-800">{{ $formData['status'] }}</span>
            </p>
        </div>

        <div class="text-right text-sm text-gray-600">
            @if ($lastSavedAt)
                <p><strong>Last Saved:</strong> {{ $lastSavedAt->setTimezone('Africa/Cairo')->format('h:i:s A') }}</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2">
            <form wire:submit.prevent="submit">
                {{ $this->form }}
            </form>

            <div class="mt-6 flex gap-4">
                <button wire:click="saveDraft"
                    type="button"
                    class="bg-yellow-400 text-black font-semibold px-5 py-3 rounded-lg shadow hover:bg-yellow-500 transition">
                    💾 Save Draft
                </button>

                @if($progress === 100)
                    <button wire:click="finalizeReport"
                        type="button"
                        class="bg-green-500 text-white font-semibold px-5 py-3 rounded-lg shadow hover:bg-green-600 transition">
                        ✅ Finalize Report
                    </button>
                @endif
            </div>
        </div>

        <div class="space-y-4">
            <x-filament::card>
                <h3 class="text-base font-semibold text-gray-800 mb-2">👦 Child Information</h3>
                <div class="text-sm text-gray-700 space-y-1">
                    @if(isset($formData['child_id']) && $formData['child_id'])
                        @php
                            $selectedChild = \App\Models\CognifyChild::find($formData['child_id']);
                        @endphp

                        @if($selectedChild)
                            <p><strong>Name:</strong> <span class="text-gray-900">{{ $selectedChild->fullName }}</span></p>
                            <p><strong>Age:</strong> <span class="text-gray-900">{{ $selectedChild->age ?? '-' }}</span></p>
                            <p><strong>School:</strong> <span class="text-gray-900">{{ $selectedChild->schoolName ?? '-' }}</span></p>
                        @else
                            <p class="text-red-500">No child found.</p>
                        @endif
                    @else
                        <p class="text-red-500">Please select a child to view details.</p>
                    @endif
                </div>
            </x-filament::card>
        </div>
    </div>

</x-filament-panels::page>
