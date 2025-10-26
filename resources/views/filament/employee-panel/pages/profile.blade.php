<x-filament::page>
    {{-- 🔹 Tabs Navigation --}}
    <x-employee-navigation :panel="'employee'" />

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- 👤 Professional Profile --}}
        <x-filament::card>
            <h2 class="text-lg font-bold text-gray-700 mb-4">Professional Profile</h2>

            <div class="flex flex-col items-center text-center space-y-2">
                <div class="w-20 h-20 rounded-full bg-orange-200 flex items-center justify-center">
                    <x-heroicon-o-user class="w-10 h-10 text-white" />
                </div>

                <h3 class="text-blue-700 font-bold text-lg">{{ $user->name.' '.$user->middle_name.' '.$user->last_name }}</h3>
                <p class="text-sm text-gray-500">{{ $user->current_position_title }}</p>

                <div class="text-sm mt-2 space-y-1 text-gray-600">
                    <p><strong>Experience:</strong> {{ $user->shadow_teacher_experience ?? 8 }} years</p>
                    <p><strong>Rating:</strong> {{ $rating }}/5 ⭐⭐⭐⭐⭐</p>
                    <p><strong>Completed Cases:</strong> {{ $completedCases }}</p>
                </div>
            </div>
        </x-filament::card>

        {{-- 📈 Performance Overview --}}
        <x-filament::card>
            <h2 class="text-lg font-bold text-gray-700 mb-4">Performance Overview</h2>

            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Report Quality <span class="float-right">95%</span></p>
                    <div class="w-full h-2 bg-gray-200 rounded-full">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 95%"></div>
                    </div>
                </div>

                <div>
                    <p class="text-sm text-gray-600 mb-1">On-Time Delivery <span class="float-right">98%</span></p>
                    <div class="w-full h-2 bg-gray-200 rounded-full">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 98%"></div>
                    </div>
                </div>

                <div>
                    <p class="text-sm text-gray-600 mb-1">Parent Satisfaction <span class="float-right">96%</span></p>
                    <div class="w-full h-2 bg-gray-200 rounded-full">
                        <div class="bg-red-400 h-2 rounded-full" style="width: 96%"></div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center text-sm font-medium mt-6 text-gray-600">
                <div>
                    <span class="text-xl text-blue-600 font-bold">{{ $activeCases }}</span><br>
                    Active Cases
                </div>
                <div>
                    <span class="text-xl text-blue-600 font-bold">{{ $casesThisMonth }}</span><br>
                    This Month
                </div>
            </div>
        </x-filament::card>
        {{-- change password --}}
        <x-filament::card class="bg-white rounded-lg border border-gray-200 p-8 space-y-8">

        {{-- Title --}}
        <div class="space-y-1 text-center">
            <h2 class="text-2xl font-bold text-gray-800">Change Password</h2>
            <p class="text-sm text-gray-500">Update your password to keep your account secure</p>
        </div>
    
        {{-- Form --}}
        <form wire:submit.prevent="changePassword" class="space-y-6">
    
            @foreach([
                'current_password' => ['label' => 'Current Password', 'placeholder' => 'Enter current password'],
                'new_password' => ['label' => 'New Password', 'placeholder' => 'At least 8 characters'],
                'new_password_confirmation' => ['label' => 'Confirm Password', 'placeholder' => 'Repeat new password']
            ] as $field => $config)
    
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">{{ $config['label'] }}</label>
    
                    <div class="relative">
                        <x-filament::input
                            :type="$passwordFieldsVisibility[$field] ? 'text' : 'password'"
                            wire:model.defer="{{ $field }}"
                            placeholder="{{ $config['placeholder'] }}"
                            required
                            minlength="{{ $field === 'new_password' ? 8 : null }}"
                            class="w-full bg-gray-50 border border-gray-300 rounded-lg text-sm px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"
                            style="border: 1px solid #eee"
                        />
                    
                        {{-- Eye Toggle --}}
                        <button style="right: 20px" type="button"
                            wire:click="togglePasswordVisibility('{{ $field }}')"
                            class="absolute top-1/2 -translate-y-1/2 flex items-center text-gray-400 hover:text-primary-500 transition"
                            tabindex="-1"
                        >
                            @if($passwordFieldsVisibility[$field])
                                {{-- Eye Open --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            @else
                                {{-- Eye Off --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.418 0-8-3.134-8-7 0-1.237.325-2.4.9-3.4M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                                </svg>
                            @endif
                        </button>
                    </div>
                    
    
                    {{-- Password Hint --}}
                    @if($field === 'new_password')
                        <p class="text-xs text-gray-500 mt-1">Must be at least 8 characters, include numbers & symbols.</p>
                    @endif
                </div>
    
            @endforeach
    
            {{-- Submit --}}
            <x-filament::button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold py-3 rounded-md transition">
                Update Password
            </x-filament::button>
    
        </form>
    
    </x-filament::card>
    


    </div>
</x-filament::page>