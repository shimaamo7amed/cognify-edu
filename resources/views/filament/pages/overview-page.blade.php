<x-filament::page>
    {{-- ✅ Header with Navigation --}}
    <x-header-navigation :panel="'cognify'" />


    {{-- ✅ Recent Requests Section --}}
    <x-filament::card class="mb-6 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">📌 Recent Requests</h2>
            <a href="{{ \App\Filament\Pages\AllRequests::getUrl(panel: 'cognify') }}"
               class="bg-primary-100 hover:bg-primary-200 text-primary-800 text-sm font-medium px-4 py-2 rounded-md transition">
                View All
            </a>
        </div>

        {{-- Grid of Requests --}}
        <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($this->cases as $case)
            @if($case->child)
                <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center gap-4">
                        {{-- Profile Image --}}
                        <div class="w-16 h-16 rounded-full overflow-hidden bg-gray-100 shrink-0 border border-gray-300">
                            @if($case->child->childPhoto)
                                <img src="{{ asset('storage/' . $case->child->childPhoto) }}"
                                    alt="{{ $case->child->fullName }}"
                                    class="object-cover w-full h-full">
                            @else 
                            <img src="{{ asset('storage/children/images/default.jpg') }}"
                            alt="Default Image"
                                    class="object-cover w-full h-full">
                            @endif
                        </div>
                        {{-- Info --}}
                        <div class="flex-1 space-y-1">
                            <h3 class="text-base font-semibold text-gray-800 truncate">
                                👶 {{ $case->child->fullName }}
                            </h3>
                            <p class="text-sm text-gray-500 truncate">
                                👨‍👩‍👧 {{ $case->child->parent->name ?? 'N/A' }}
                            </p>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-xs font-medium text-green-600 flex items-center gap-1">
                                    <x-heroicon-o-check-circle class="w-4 h-4" />
                                    {{ ucfirst($case->status ?? 'N/A') }}
                                </span>
                                <a href="{{ url(request()->segment(1) . '/view-request/' . $case->id) }}"
                                    class="text-sm text-primary-600 hover:underline font-medium">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <p class="text-sm text-gray-500">No recent requests found.</p>
        @endforelse
        
        </div>
    </x-filament::card>
</x-filament::page>
