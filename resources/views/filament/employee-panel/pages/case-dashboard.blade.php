<x-filament::page>

    {{-- ðŸ”¹ Tabs Navigation --}}
            <x-employee-navigation :panel="'employee'" />
    {{-- ðŸ“Š Overview Stats in a Large Card --}}
    {{-- <x-filament::card class="bg-white rounded-xl shadow p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="flex items-center space-x-4">
                <div class="bg-blue-100 p-3 rounded-full">
                    <x-heroicon-o-document-text class="w-6 h-6 text-blue-600" />
                </div>
                <div>
                    <div class="text-sm text-gray-500">Active Cases</div>
                    <div class="text-2xl font-bold text-gray-800">{{ $stats['active'] }}</div>
                </div>
            </div>


            <div class="flex items-center space-x-4">
                <div class="bg-green-100 p-3 rounded-full">
                    <x-heroicon-o-check-circle class="w-6 h-6 text-green-600" />
                </div>
                <div>
                    <div class="text-sm text-gray-500">Completed Cases</div>
                    <div class="text-2xl font-bold text-gray-800">{{ $stats['completed'] }}</div>
                </div>
            </div>


            <div class="flex items-center space-x-4">
                <div class="bg-yellow-100 p-3 rounded-full">
                    <x-heroicon-o-clock class="w-6 h-6 text-yellow-600" />
                </div>
                <div>
                    <div class="text-sm text-gray-500">Pending Reports</div>
                    <div class="text-2xl font-bold text-gray-800">{{ $stats['pending_reports'] }}</div>
                </div>
            </div>


            <div class="flex items-center space-x-4">
                <div class="bg-orange-100 p-3 rounded-full">
                    <x-heroicon-o-star class="w-6 h-6 text-orange-500" />
                </div>
                <div>
                    <div class="text-sm text-gray-500">Rating</div>
                    <div class="text-2xl font-bold text-gray-800">{{ $stats['rating'] }}/5</div>
                </div>
            </div>
        </div>
    </x-filament::card> --}}
    {{-- ðŸ§¾ Recent Cases --}}

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Recent Cases</h2>
        <a href="{{ route('filament.employeePanel.pages.all-requests') }}" class="bg-yellow-300 px-4 py-2 text-sm font-bold rounded hover:bg-yellow-400 text-black">
            View All Cases
        </a>
        </div>
        <div class="grid md:grid-cols-3 gap-4 mb-10">
            {{-- Section 1: Assigned Children --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:col-span-2">
            <h2 class="text-lg font-semibold col-span-full text-gray-700 mb-2">Assigned Children (Regular Cases)</h2>

            @foreach(auth()->user()->assignedChildren->take(3) as $child)
                <x-filament::card class="hover:shadow-lg transition">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full overflow-hidden border border-gray-300">
                            <img src="{{ $child->childPhoto ? asset('storage/' . $child->childPhoto) : asset('storage/children/images/default.jpg') }}"
                                alt="{{ $child->fullName }}"
                                class="object-cover w-full h-full">
                        </div>
                        <div class="flex-1">
                            <h4 class="text-base font-semibold text-gray-900">{{ $child->fullName }}</h4>
                            <p class="text-sm text-gray-500">{{ $child->schoolName }} â€¢ Age {{ $child->age }}</p>
                            <div class="flex flex-wrap gap-1 mt-1">
                                <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full">{{ $child->priority }}</span>
                            </div>
 <a href="{{ route('filament.employeePanel.pages.employee-child-view', $child->id) }}"
   class="inline-block mt-1 text-sm text-primary-600 hover:underline">
    View
</a>                  </div>





                    </div>
                </x-filament::card>
            @endforeach

            @if(auth()->user()->assignedChildren->count() > 3)
                <div class="text-center mt-4 col-span-full">
                    <a href="#"
                    class="text-sm text-primary-600 hover:underline">View All Assigned Children</a>
                </div>
            @endif
        </div>

        {{-- Section 2: Observation Cases --}}
        <div class="grid grid-cols-1 gap-4">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Observation Cases</h2>

            @foreach($cases->take(2) as $case)
                <x-filament::card class="hover:shadow-lg transition">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full overflow-hidden border border-gray-300">
                            <img src="{{ $case->child->childPhoto ? asset('storage/' . $case->child->childPhoto) : asset('storage/children/images/default.jpg') }}"
                                alt="{{ $case->child->fullName }}"
                                class="object-cover w-full h-full">
                        </div>
                        <div class="flex-1">
                            <h4 class="text-base font-semibold text-gray-900">{{ $case->child->fullName ?? 'Unnamed' }}</h4>
                            <p class="text-sm text-gray-500">{{ $case->child->schoolName }} â€¢ Age {{ $case->child->age }}</p>
                            <div class="flex flex-wrap gap-1 mt-1">
                                <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded-full">{{ $case->status }}</span>
                                <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full">{{ $case->child->priority }}</span>
                            </div>
                            <a href="{{ url(request()->segment(1).'/view-request/'.$case->id) }}"
                            class="inline-block mt-1 text-sm text-primary-600 hover:underline">View</a>
                        </div>
                    </div>
                </x-filament::card>
            @endforeach
        </div>
    </div>


    {{-- âš¡ Quick Actions --}}
    <div class="grid md:grid-cols-3 gap-4">
        {{-- Create Report --}}
        <x-filament::card class="flex items-center space-x-4 hover:shadow-lg transition">
            <x-heroicon-o-plus-circle class="w-6 h-6 text-primary-600" />
            <div>
            <a href="{{ route('filament.employeePanel.pages.daily-report.create')}}">
                Create Daily Report
            </a>

                <p class="text-sm text-gray-500">Start a new observation report</p>
            </div>
        </x-filament::card>

        {{-- View Schedule --}}
        <x-filament::card class="flex items-center space-x-4 hover:shadow-lg transition">
            <x-heroicon-o-calendar-days class="w-6 h-6 text-primary-600" />
            <div>
                <a href="{{ route('filament.employeePanel.pages.view-schedule') }}" class="text-base font-semibold hover:underline">
                    View Schedule
                </a>
                <p class="text-sm text-gray-500">See your upcoming sessions</p>
            </div>
        </x-filament::card>

        {{-- My Students --}}
        <x-filament::card class="flex items-center space-x-4 hover:shadow-lg transition">
            <x-heroicon-o-user-group class="w-6 h-6 text-primary-600" />
            <div>
                <a href="{{ route('filament.employeePanel.pages.all-requests') }}" class="text-base font-semibold hover:underline">
                    My Students
                </a>
                <p class="text-sm text-gray-500">Manage your assigned children</p>
            </div>
        </x-filament::card>
    </div>
</x-filament::page>
