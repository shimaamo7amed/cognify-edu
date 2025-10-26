<x-filament::page>
    <div class="w-full max-w-7xl mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Parent Info -->
            <div class="w-full lg:w-1/2 bg-white p-6 rounded-xl shadow border border-gray-100">
                <div class="flex items-center gap-2 mb-4">
                    <x-heroicon-o-user class="w-5 h-5 text-primary-600" />
                    <h3 class="text-lg font-semibold">Parent Information</h3>
                </div>
                <div class="grid grid-cols-1 gap-4 text-sm text-gray-700">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-user class="w-4 h-4 text-primary-600" />
                        <p><strong>Name:</strong> {{ $child->parent->name ?? 'N/A' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-envelope class="w-4 h-4 text-primary-600" />
                        <p><strong>Email:</strong> {{ $child->parent->email ?? 'N/A' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-phone class="w-4 h-4 text-primary-600" />
                        <p><strong>Phone:</strong> {{ $child->parent->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-home class="w-4 h-4 text-primary-600" />
                        <p><strong>Address:</strong> {{ $child->parent->address ?? 'N/A' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-map class="w-4 h-4 text-primary-600" />
                        <p><strong>Governorate:</strong> {{ $child->parent->governorate ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Child Info -->
            <div class="w-full lg:w-1/2 bg-white p-6 rounded-xl shadow border border-gray-100">
                <div class="flex items-center gap-2 mb-4">
                    <x-heroicon-o-user class="w-5 h-5 text-primary-600" />
                    <h3 class="text-lg font-semibold">Child Information</h3>
                </div>
                <div class="grid grid-cols-1 gap-4 text-sm text-gray-700">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-user class="w-4 h-4 text-primary-600" />
                        <p><strong>Name:</strong> {{ $child->fullName ?? 'N/A' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-envelope class="w-4 h-4 text-primary-600" />
                        <p><strong>Age:</strong> {{ $child->age ?? 'N/A' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-phone class="w-4 h-4 text-primary-600" />
                        <p><strong>School:</strong> {{ $child->schoolName ?? 'N/A' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-home class="w-4 h-4 text-primary-600" />
                        <p><strong>Address:</strong> {{ $child->homeAddress ?? 'N/A' }}</p>
                    </div>

                    @if($child->textDescription)
                        <p><strong>Text Description:</strong></p>
                        <p class="bg-blue-50 text-blue-800 p-4 rounded text-sm">
                            {{ $child->textDescription }}
                        </p>
                    @elseif($child->voiceRecording)
                        <p><strong>Voice Recording:</strong></p>
                        <audio controls class="w-full mt-2">
                            <source src="{{ asset('storage/' . $child->voiceRecording) }}" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                    @else
                        <p class="text-gray-500 italic">No description or audio available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-filament::page>