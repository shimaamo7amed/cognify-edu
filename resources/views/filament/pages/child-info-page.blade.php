<x-filament-panels::page>
    <x-request.header :requestCase="$requestCase" />
    <x-request.tabs :recordId="$requestCase->id" />

    {{-- ✅ Child Profile --}}
    <div class="bg-white p-6 rounded-xl shadow border border-gray-100 mb-6 flex flex-col sm:flex-row gap-6">
        {{-- Image --}}
        <div class="@if($requestCase->child->childPhoto) w-16 h-16 @else w-24 h-24 @endif rounded-full overflow-hidden border-4 border-gray-300 shadow-md">
            @if($requestCase->child->childPhoto)
                <img src="{{ asset('storage/' . $requestCase->child->childPhoto) }}"
                    alt="{{ $requestCase->child->fullName }}"
                    class="w-full h-full object-cover">
            @else
                <img src="{{ asset('storage/children/images/default.jpg') }}"
                    alt="Default Image"
                    class="w-full h-full object-cover">
            @endif
        </div>
        {{-- Info --}}
        <div class="flex-1 space-y-2 text-sm text-gray-700">
            <h3 class="text-lg font-semibold text-red-600 flex items-center gap-2">
                <x-heroicon-o-user class="w-5 h-5 text-red-600" />
                Child Profile
            </h3>
            <p><strong>Name:</strong> {{ $requestCase->child->fullName }}</p>
            <p><strong>Age:</strong> {{ $requestCase->child->age }}</p>
            <p><strong>Gender:</strong> {{ $requestCase->child->gender }}</p>
            <p><strong>School:</strong> {{ $requestCase->child->schoolName }}</p>
            <p><strong>School Address:</strong> {{ $requestCase->child->schoolAddress }}</p>
            <p><strong>Home Address:</strong> {{ $requestCase->child->homeAddress }}</p>
    
            <div class="flex items-center gap-2 flex-wrap">
                <strong>Medical Conditions:</strong>
                @if($requestCase->child->medicalConditions)
                    @foreach($requestCase->child->medicalConditions as $medicalCondition)
                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs">{{ $medicalCondition }}</span>
                    @endforeach
                @else
                    <span class="text-gray-500 italic">No medical conditions</span>
                @endif
            </div>
        </div>
    </div>
    

    {{-- ✅ Allergy Information --}}
    <div class="bg-white p-6 rounded-xl shadow border border-gray-100 mb-6 text-sm text-gray-700">
        <div class="flex items-center gap-2 mb-4">
            <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-orange-500" />
            <h3 class="text-base font-semibold">Allergy Information</h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div>
                <p class="font-medium text-gray-600">Food Allergies</p>
                <div class="flex flex-wrap gap-2 mt-1">
                    @if($requestCase->child->foodAllergies)
                        @foreach($requestCase->child->foodAllergies as $foodAllergy)
                            <span class="bg-orange-100 text-orange-800 text-xs font-semibold px-2 py-1 rounded-full">{{ $foodAllergy }}</span>
                        @endforeach
                    @else
                        <span class="text-gray-500 italic text-xs">No food allergies</span>
                    @endif
                </div>
            </div>

            <div>
                <p class="font-medium text-gray-600">Environmental</p>
                <div class="flex flex-wrap gap-2 mt-1">
                    @if($requestCase->child->environmentalAllergies)
                        @foreach($requestCase->child->environmentalAllergies as $environmentalAllergy)
                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full">{{ $environmentalAllergy }}</span>
                        @endforeach
                    @else
                        <span class="text-gray-500 italic text-xs">No environmental allergies</span>
                    @endif
                </div>
            </div>

            <div>
                <p class="font-medium text-gray-600">Severity</p>
                <div class="mt-1">
                    @if($requestCase->child->severityLevels)
                        @foreach($requestCase->child->severityLevels as $severityLevel)
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded-full">{{ $severityLevel }}</span>
                        @endforeach
                    @else
                        <span class="text-gray-500 italic text-xs">No severity levels</span>
                    @endif
                </div>
            </div>

            <div>
                <p class="font-medium text-gray-600">Medication Allergies</p>
                <div class="flex flex-wrap gap-2 mt-1">
                    @if($requestCase->child->medicationAllergies)
                        @foreach($requestCase->child->medicationAllergies as $medicationAllergy)
                            <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded-full">{{ $medicationAllergy }}</span>
                        @endforeach
                    @else
                        <span class="text-gray-500 italic text-xs">No medication allergies</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ Case Description --}}
    <div class="bg-white p-6 rounded-xl shadow border border-gray-100 text-sm text-gray-700">
        <div class="flex items-center gap-2 mb-4">
            <x-heroicon-o-clipboard-document-list class="w-5 h-5 text-blue-600" />
            <h3 class="text-base font-semibold">Case Description</h3>
        </div>
    
        @if($requestCase->child->textDescription)
            <p class="bg-blue-50 text-blue-800 p-4 rounded text-sm">
                {{ $requestCase->child->textDescription }}
            </p>
        @elseif($requestCase->child->voiceRecording)
            <audio controls class="w-full mt-2">
                <source src="{{ asset('storage/' . $requestCase->child->voiceRecording) }}" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>
        @else
            <p class="text-gray-500 italic">No description or audio available.</p>
        @endif
    </div>
    <x-request.sidebar :requestCase="$requestCase" :acceptedEmployees="$this->acceptedEmployees ?? collect()" />

</x-filament-panels::page>