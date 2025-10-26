<div class="flex justify-between items-center mb-6">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-4">
        <a href="{{ \App\Filament\Pages\AllRequests::getUrl() }}"
            class="inline-flex items-center text-sm text-gray-600 hover:text-primary-600 transition">
            <x-heroicon-o-arrow-left class="w-4 h-4 mr-1" />
            Back to All Requests
        </a>

        <div class="text-sm text-gray-800">
            <span class="font-medium">Request ID:</span>
            <span class="text-primary-700 font-semibold">{{ $requestCase->id }}</span>
        </div>

        @php
            $statusColors = [
                'new_request' => 'bg-gray-200 text-gray-700',
                'under_review' => 'bg-blue-100 text-blue-800',
                'approved' => 'bg-green-100 text-green-800',
                'assigned' => 'bg-indigo-100 text-indigo-800',
                'scheduled' => 'bg-yellow-100 text-yellow-800',
                'completed' => 'bg-green-200 text-green-900',
                'cancelled' => 'bg-red-100 text-red-800',
            ];
        @endphp

        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$requestCase->status] }}">
            {{ ucfirst(str_replace('_', ' ', $requestCase->status)) }}
        </span>
    </div>

    {{-- < href="{{ \App\Filament\Pages\CreateReportPage::getUrl(['record' => $requestCase->id]) }}">
        <x-filament::button size="sm" color="primary" icon="heroicon-o-plus" class="shrink-0">
            Create Report
        </x-filament::button> --}}
      {{-- @if ($requestCase->employee_id === auth()->id()) --}}
    {{-- dd([$requestCase->employee_id, auth()->id()]) --}}

    <a href="{{ \App\Filament\Pages\CreateReportPage::getUrl(['record' => $requestCase->id]) }}">
        <x-filament::button size="sm" color="primary" icon="heroicon-o-plus" class="shrink-0">
            Create Report
        </x-filament::button>
    </a>
{{-- @endif --}}
</div>
