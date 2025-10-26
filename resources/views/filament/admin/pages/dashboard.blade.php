<x-filament::page>
    {{-- 🔹 Header --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-primary-800">Recent Requests</h2>
        <a href="{{ \App\Filament\Pages\AllRequests::getUrl(panel: 'cognify') }}"
            class="bg-yellow-300 hover:bg-yellow-400 text-black text-sm font-semibold px-4 py-2 rounded transition">
            View All
        </a>
    </div>

    {{-- 🔸 Overview Cards --}}
    <div class="grid md:grid-cols-3 gap-4 mb-10">
        <x-filament::card class="flex items-center space-x-4 hover:shadow-lg transition">
            <x-heroicon-o-user-group class="w-6 h-6 text-primary-600" />
            <div>
                <p class="text-base font-semibold text-gray-800">Overview</p>
                <p class="text-sm text-gray-500">Requests by all users</p>
            </div>
        </x-filament::card>

        <x-filament::card class="flex items-center space-x-4 hover:shadow-lg transition">
            <x-heroicon-o-calendar-days class="w-6 h-6 text-primary-600" />
            <div>
                <p class="text-base font-semibold text-gray-800">Sessions</p>
                <p class="text-sm text-gray-500">Upcoming & Past</p>
            </div>
        </x-filament::card>

        <x-filament::card class="flex items-center space-x-4 hover:shadow-lg transition">
            <x-heroicon-o-document-text class="w-6 h-6 text-primary-600" />
            <div>
                <p class="text-base font-semibold text-gray-800">Reports</p>
                <p class="text-sm text-gray-500">All created observation reports</p>
            </div>
        </x-filament::card>
    </div>

    {{-- 🔹 Recent Requests Cards --}}
    <div class="grid md:grid-cols-3 gap-4">
        @forelse($this->cases as $case)
            <x-filament::card class="border border-gray-200 shadow-sm hover:shadow-md transition">
                <div class="flex flex-col items-center space-y-3 text-center">
                   <div class="w-16 h-16 rounded-full overflow-hidden bg-gray-100 shrink-0 border border-gray-300">
                            @if($case->child->childPhoto)
                                <img src="{{ asset('storage/' . $case->child->childPhoto) }}"
                                    alt="{{ $case->child->name }}"
                                    class="object-cover w-full h-full">
                            @else
                            <img src="{{ asset('storage/children/images/default.jpg') }}"
                            alt="Default Image"
                                    class="object-cover w-full h-full">
                            @endif
                        </div>

                    <h3 class="text-lg font-semibold text-gray-800">{{ $case->child->fullName ?? 'Unnamed' }}</h3>
                    <p class="text-sm text-gray-500">
                        {{ $case->child->schoolName ?? 'Unknown School' }} • Age {{ $case->child->age ?? '?' }}
                    </p>

                    <div class="text-sm font-medium text-green-600 flex items-center gap-1">
                        <x-heroicon-o-check-circle class="w-4 h-4" />
                        {{ ucfirst($case->status ?? 'N/A') }}
                    </div>

                    <a href="{{ url(request()->segment(1) . '/view-request/' . $case->id) }}"
                       class="text-primary-600 hover:underline">
                        View
                    </a>
                </div>
            </x-filament::card>
        @empty
            <x-filament::card class="text-center py-6">
                <p class="text-gray-500">No requests found.</p>
            </x-filament::card>
        @endforelse
    </div>
</x-filament::page>
