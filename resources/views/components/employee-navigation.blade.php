    {{-- 🔹 Tabs Navigation --}}
    <div class="flex space-x-6 border-b mb-6 text-gray-600 text-sm font-medium">
        <a
        href="{{ route('filament.employeePanel.pages.case-dashboard') }}"
        class="py-2 px-4 border-b-2
            {{ request()->routeIs('filament.employeePanel.pages.case-dashboard')
                ? 'border-yellow-400 bg-yellow-300 font-bold text-black'
                : 'hover:border-yellow-300' }}">
        Overview
        </a>

        <a class="py-2 px-4 hover:text-primary-600" href="{{ route('filament.employeePanel.pages.all-requests') }}">My Cases</a>
        <a class="py-2 px-4 hover:text-primary-600 {{ request()->routeIs('filament.employeePanel.pages.reports') ? 'text-primary-600 font-semibold' : '' }}"
            href="{{ route('filament.employeePanel.pages.reports') }}">
            Reports
        </a>
        <a class="py-2 px-4 hover:text-primary-600" 
            href="{{ route('filament.employeePanel.pages.profile') }}">
            Profile
        </a>
    </div>