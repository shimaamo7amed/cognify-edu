<x-filament-panels::page>
        {{-- ✅ Header Section --}}
        <x-request.header :requestCase="$requestCase" />


        {{-- ✅ Tabs --}}
        <x-request.tabs :recordId="$requestCase->id" />

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Documents & Files</h2>
        <x-filament::button color="warning">
            <x-heroicon-o-arrow-down-tray class="w-5 h-5 mr-1" />
            Download All
        </x-filament::button>
    </div>

    <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Example Document 1 --}}
            <div class="border rounded-lg p-4 space-y-3">
                <div class="flex items-center gap-3">
                    <x-heroicon-o-document class="w-6 h-6 text-blue-600" />
                    <div>
                        <h4 class="text-blue-800 font-semibold">Registration Form</h4>
                        <span class="text-xs text-gray-500">PDF • 245 KB</span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="#" target="_blank"
                       class="bg-yellow-400 text-black px-4 py-1 rounded text-sm font-medium flex items-center gap-1">
                        <x-heroicon-o-eye class="w-4 h-4" /> View
                    </a>
                    <a href="#" download
                       class="bg-yellow-400 text-black px-4 py-1 rounded text-sm font-medium flex items-center gap-1">
                        <x-heroicon-o-arrow-down-tray class="w-4 h-4" /> Download
                    </a>
                </div>
            </div>

            {{-- Example Document 2 --}}
            <div class="border rounded-lg p-4 space-y-3">
                <div class="flex items-center gap-3">
                    <x-heroicon-o-user-circle class="w-6 h-6 text-green-600" />
                    <div>
                        <h4 class="text-green-800 font-semibold">Child Profile Photo</h4>
                        <span class="text-xs text-gray-500">JPG • 1.2 MB</span>
                    </div>
                </div>
                @php
                $imagePath = $requestCase->child->image
                    ? asset('storage/' . $requestCase->child->image)
                    : asset('storage/children/images/default.jpg');
                @endphp
                <div class="flex gap-2">
                    <a href="{{ $imagePath }}" target="_blank"
                    class="bg-yellow-400 text-black px-4 py-1 rounded text-sm font-medium flex items-center gap-1">
                        <x-heroicon-o-eye class="w-4 h-4" /> View
                    </a>

                    <a href="{{ $imagePath }}" download
                    class="bg-yellow-400 text-black px-4 py-1 rounded text-sm font-medium flex items-center gap-1">
                        <x-heroicon-o-arrow-down-tray class="w-4 h-4" /> Download
                    </a>
                </div>
            </div>

            {{-- Example Document 3 --}}
            <div class="border rounded-lg p-4 space-y-3">
                <div class="flex items-center gap-3">
                    <x-heroicon-o-currency-dollar class="w-6 h-6 text-purple-600" />
                    <div>
                        <h4 class="text-purple-800 font-semibold">Payment Receipt</h4>
                        <span class="text-xs text-gray-500">PDF • 89 KB</span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="#" target="_blank"
                    class="bg-yellow-400 text-black px-4 py-1 rounded text-sm font-medium flex items-center gap-1">
                        <x-heroicon-o-eye class="w-4 h-4" /> View
                    </a>
                    <a href="#" download
                    class="bg-yellow-400 text-black px-4 py-1 rounded text-sm font-medium flex items-center gap-1">
                        <x-heroicon-o-arrow-down-tray class="w-4 h-4" /> Download
                    </a>
                </div>

            </div>
            {{-- <x-request.sidebar :requestCase="$requestCase" :acceptedEmployees="$this->acceptedEmployees ?? collect()" /> --}}

        </div>
    </div>
</x-filament-panels::page>
