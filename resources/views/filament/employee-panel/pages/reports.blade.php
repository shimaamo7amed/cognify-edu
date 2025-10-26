<x-filament::page>
    <div class="space-y-6">

        <div class="bg-white p-4 rounded-xl border shadow-sm flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <x-heroicon-o-chart-bar class="w-5 h-5 text-indigo-500" />
                    Reports Dashboard
                </h1>
                <p class="text-xs text-gray-500">Observation & Daily Reports</p>
            </div>

            <div class="flex items-center gap-2 relative" x-data="{ open: false }" x-cloak>

                <div class="bg-gray-50 px-3 py-1 rounded-lg text-center border">
                    <p class="text-[10px] text-gray-500 leading-tight">Total</p>
                    <p class="text-base font-semibold text-gray-800 leading-tight">
                        {{ $observationReports->count() + $dailyReports->count() }}
                    </p>
                </div>

                <button @click="open = !open"
                    class="p-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-500 rounded-full transition border border-indigo-100">
                    <x-heroicon-o-funnel class="w-4 h-4" />
                </button>

                <div x-show="open" @click.away="open = false" x-transition
                    class="absolute top-12 right-0 bg-white rounded-lg shadow-lg border w-56 p-4 z-50">

                    <form method="GET" action="{{ route('filament.employeePanel.pages.reports') }}" class="space-y-3">

                        <label class="text-xs font-semibold text-gray-500">Report Type</label>
                        <select name="filter" onchange="this.form.submit()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All</option>
                            <option value="observation" {{ $filter == 'observation' ? 'selected' : '' }}>Observation</option>
                            <option value="daily" {{ $filter == 'daily' ? 'selected' : '' }}>Daily</option>
                        </select>

                        <label class="text-xs font-semibold text-gray-500 mt-2">Child</label>
                        <select name="child_id" onchange="this.form.submit()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            <option value="">All Children</option>
                            @foreach($children as $id => $name)
                                <option value="{{ $id }}" {{ $childId == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>

                    </form>
                </div>

                <button type="button" onclick="window.location.href='{{ route('filament.employeePanel.pages.reports') }}'"
                    class="mt-2 text-xs text-gray-500 hover:underline w-full text-center">
                    Reset Filters
                </button>

            </div>
        </div>

        <div class="space-y-4">

            @if($observationReports->isNotEmpty())
                <div>
                    <h2 class="text-sm font-semibold text-blue-600 mb-2">Observation Reports</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        @foreach ($observationReports as $report)
                            <div class="bg-white p-3 rounded-lg border shadow-sm text-xs">
                                <p class="font-semibold text-gray-800">Child: {{ $report->child->fullName ?? 'Unnamed' }}</p>
                                <p class="text-gray-500 text-[10px]">Date: {{ $report->created_at->format('M d, Y') }}</p>
                                <span class="inline-block mt-1 px-2 py-0.5 text-[10px] rounded-full 
                                    @if($report->status == 'finalized') bg-green-100 text-green-700 
                                    @elseif($report->status == 'draft') bg-yellow-100 text-yellow-700 
                                    @else bg-gray-100 text-gray-700 @endif">
                                    Status: {{ ucfirst($report->status) }}
                                </span>

                                <div class="mt-2">
                                    @if($report->status == 'draft')
                                        <a href="{{ \App\Filament\Pages\CreateReportPage::getUrl(['record' => $report->observationCase->id]) }}"
                                            class="text-yellow-600 hover:text-yellow-800 text-xs">
                                            ✏️ Resume Draft
                                        </a>
                                    @else
                                        <a href="{{ \App\Filament\EmployeePanel\Pages\ViewReport::getUrl(['type' => 'observation', 'id' => $report->id]) }}"
                                        class="text-indigo-500 hover:underline text-xs">
                                            View Report
                                        </a>
                                    @endif

                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($dailyReports->isNotEmpty())
                <div>
                    <h2 class="text-sm font-semibold text-green-600 mb-2">Daily Reports</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        @foreach ($dailyReports as $report)
                            <div class="bg-white p-3 rounded-lg border shadow-sm text-xs">
                                <p class="font-semibold text-gray-800">Child: {{ $report->child->fullName ?? 'Unnamed' }}</p>
                                <p class="text-gray-500 text-[10px]">Date: {{ $report->report_date }}</p>
                                <span class="inline-block mt-1 px-2 py-0.5 text-[10px] rounded-full 
                                    @if($report->status == 'finalized') bg-green-100 text-green-700 
                                    @elseif($report->status == 'draft') bg-yellow-100 text-yellow-700 
                                    @else bg-gray-100 text-gray-700 @endif">
                                    Status: {{ ucfirst($report->status) }}
                                </span>

                                <div class="mt-2">
                                    @if($report->status == 'draft')
                                        <a href="{{ \App\Filament\EmployeePanel\Pages\CreateDailyReport::getUrl(['draft_id' => $report->id]) }}"
                                        class="text-yellow-500 hover:underline text-xs">
                                            ✏️ Resume Draft
                                        </a>
                                    @else
                                        <a href="{{ \App\Filament\EmployeePanel\Pages\ViewReport::getUrl(['type' => 'daily', 'id' => $report->id]) }}"
                                        class="text-green-500 hover:underline text-xs">
                                            View Report
                                        </a>
                                    @endif

                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($observationReports->isEmpty() && $dailyReports->isEmpty())
                <div class="text-center text-xs text-gray-500 py-10">
                    No reports found.
                </div>
            @endif

        </div>

    </div>
</x-filament::page>
