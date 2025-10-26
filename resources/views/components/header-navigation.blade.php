    <div class="flex justify-between items-center mb-6">
        <div class="flex gap-6">
            {{-- Overview --}}
            <a href="{{ \App\Filament\Pages\OverviewPage::getUrl(panel: 'cognify') }}"
                class="flex items-center gap-1 text-sm font-medium px-2 py-1 rounded transition
                {{ request()->routeIs('filament.cognify.pages.overview-page') ? 'text-primary-600 bg-primary-50' : 'text-gray-600 hover:text-primary-500 hover:bg-gray-50' }}">
                <x-heroicon-o-home class="w-4 h-4" />
                Overview
            </a>
    
            {{-- Requests --}}
            @if(auth()->user()->hasRole('admin'))
                <a href="{{ \App\Filament\Pages\AllRequests::getUrl(panel: 'cognify') }}"
                    class="flex items-center gap-1 text-sm font-medium px-2 py-1 rounded transition
                    {{ request()->routeIs('filament.cognify.pages.all-requests') ? 'text-primary-600 bg-primary-50' : 'text-gray-600 hover:text-primary-500 hover:bg-gray-50' }}">
                    <x-heroicon-o-clipboard-document-list class="w-4 h-4" />
                    Requests
                </a>
            @endif
    
            {{-- Teachers --}}
            @if(auth()->user()->hasRole('admin'))
                <a href="{{ \App\Filament\Resources\EmployeeResource::getUrl(name: 'index', panel: 'cognify') }}"
                    class="flex items-center gap-1 text-sm font-medium px-2 py-1 rounded transition
                    {{ request()->routeIs('filament.cognify.resources.employee.index') ? 'text-primary-600 bg-primary-50' : 'text-gray-600 hover:text-primary-500 hover:bg-gray-50' }}">
                    <x-heroicon-o-user-group class="w-4 h-4" />
                    Teachers
                </a>
            @endif
    
            {{-- Reports --}}
            @if(auth()->user()->hasRole('admin'))
            <a href="{{ \App\Filament\Pages\ChildReports::getUrl(panel: 'cognify') }}"
            class="flex items-center gap-1 text-sm font-medium px-2 py-1 rounded transition
                    {{ request()->is('cognify/reports*') ? 'text-primary-600 bg-primary-50' : 'text-gray-600 hover:text-primary-500 hover:bg-gray-50' }}">
                    <x-heroicon-o-document-text class="w-4 h-4" />
                    Reports
                </a>
            @endif
        </div>
    </div>