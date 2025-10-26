<x-filament-panels::page>
    
    <div>
        {{-- ✅ Flash Message --}}
        @if(session('message'))
            <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 rounded text-sm">
                {{ session('message') }}
            </div>
        @endif

        {{-- ✅ Header Section --}}
        <x-request.header :requestCase="$requestCase" />


        {{-- ✅ Tabs --}}
        <x-request.tabs :recordId="$requestCase->id" />


        {{-- ✅ Main Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- LEFT Content --}}
            <div class="lg:col-span-8 space-y-6">

                {{-- 🔹 Parent Information --}}
                <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
                    <div class="flex items-center gap-2 mb-4">
                        <x-heroicon-o-user class="w-5 h-5 text-primary-600" />
                        <h3 class="text-lg font-semibold">Parent Information</h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-user class="w-4 h-4 text-primary-600" />
                            <p><strong>Name:</strong> {{ $requestCase->child->parent->name }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-envelope class="w-4 h-4 text-primary-600" />
                            <p><strong>Email:</strong> {{ $requestCase->child->parent->email }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-phone class="w-4 h-4 text-primary-600" />
                            <p><strong>Phone:</strong> {{ $requestCase->child->parent->phone }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-home class="w-4 h-4 text-primary-600" />
                            <p><strong>Address:</strong> {{ $requestCase->child->parent->address }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-map class="w-4 h-4 text-primary-600" />
                            <p><strong>Governorate:</strong> {{ $requestCase->child->parent->governorate }}</p>
                        </div>
                    </div>
                </div>

                {{-- 🔹 Observation Booking --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
                        <div class="flex items-center gap-2 mb-4">
                            <x-heroicon-o-calendar class="w-5 h-5 text-primary-600" />
                            <h3 class="text-lg font-semibold">Observation Booking</h3>
                        </div>
                        <div class="space-y-2 text-sm text-gray-700">
                            <p><strong>Date:</strong> {{ $requestCase->slot_date }}</p>
                            <p><strong>Time:</strong> {{ $requestCase->slot_time }}</p>
                            <p>
                                <strong>Status:</strong>
                                <span class="inline-block px-2 py-1 bg-green-500 text-black text-xs rounded">Confirmed</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT Sidebar --}}
            <x-request.sidebar :requestCase="$requestCase" :acceptedEmployees="$this->acceptedEmployees ?? collect()" />

        </div>
    </div>
</x-filament-panels::page>
