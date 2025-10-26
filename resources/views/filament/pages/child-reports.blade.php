<x-filament::page>
    <div class="space-y-6">

        {{-- Header --}}
        <div class="bg-white p-4 rounded-xl border shadow-sm flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <x-heroicon-o-document-text class="w-5 h-5 text-indigo-500" />
                    Child Reports
                </h1>

                @if($requestCase)
                    <p class="text-xs text-gray-500 mt-1">For: {{ $requestCase->child->fullName ?? 'Unknown Child' }}</p>
                @endif
            </div>

            {{-- Filter --}}
            <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-2">
                <select name="child_id" onchange="this.form.submit()"
                    class="text-xs px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Children</option>
                    @foreach($children as $id => $name)
                        <option value="{{ $id }}" {{ request('child_id') == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- Observation Reports --}}
        <div>
            <h2 class="text-sm font-semibold text-blue-600 mb-2">Observation Reports</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @forelse ($reports as $report)
                    <div class="bg-gray-50 p-3 rounded-lg border text-xs shadow-sm">
                        <p><strong>Child:</strong> {{ $report->child->fullName ?? 'Unknown' }}</p>
                        <p><strong>Observer:</strong>
                            {{ optional($report->observationCase->employee)->name }} 
                            {{ optional($report->observationCase->employee)->middle_name }} 
                            {{ optional($report->observationCase->employee)->last_name }}
                        </p>
                        <p class="text-gray-500 text-[10px] mt-1">Date: {{ $report->observationCase->slot_date }}</p>
                        <p class="text-gray-500 text-[10px] mt-1">Status: {{ $report->delivery_status }}</p>


                        <div class="mt-2 flex gap-2">
                            @if ($report->status === 'draft')
                                <a href="{{ \App\Filament\Pages\CreateReportPage::getUrl(['record' => $report->observationCase->id]) }}"
                                    class="text-yellow-600 hover:text-yellow-800 text-xs">
                                    ✏️ Resume Draft
                                </a>



                            @elseif ($report->status === 'finalized')
                                <a href="{{ route('report.download', ['id' => $report->id]) }}" target="_blank"
                                class="text-gray-500 hover:text-gray-700 text-xs">⬇️ PDF</a>
                            @else
                                <span class="text-gray-400">Status: {{ $report->status }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-xs">No Observation Reports found.</p>
                @endforelse
            </div>

        </div>

        {{-- Daily Reports --}}
<div>
    <h2 class="text-sm font-semibold text-green-600 mb-2 mt-6">Daily Reports</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
        @forelse ($dailyReports as $report)
            <div class="bg-white p-3 rounded-lg border text-xs shadow-sm">
                <p><strong>Child:</strong> {{ $report->child->fullName ?? 'Unknown' }}</p>
                <p class="text-gray-500 text-[10px]">Date: {{ $report->report_date }}</p>

                <span class="inline-block mt-1 px-2 py-0.5 text-[10px] rounded-full 
                    @if($report->status == 'finalized') bg-green-100 text-green-700 
                    @elseif($report->status == 'draft') bg-yellow-100 text-yellow-700 
                    @else bg-gray-100 text-gray-700 @endif">
                    Status: {{ ucfirst($report->child->observationReports->delivery_status) }}
                </span>

                <div class="mt-2 flex gap-2">
                    @if($report->status == 'draft')
                        <a href="{{ \App\Filament\EmployeePanel\Pages\CreateDailyReport::getUrl(['draft_id' => $report->id]) }}"
                            class="text-yellow-500 hover:underline text-xs">✏️ Resume Draft</a>
                    @elseif($report->status == 'finalized')
                        <button wire:click="download({{ $report->id }})"
                            class="text-gray-500 hover:text-gray-700 text-xs">⬇️ PDF</button>
                    @elseif(optional($report->child->sentReports)->first()?->delivery_status == 'sent')
                        <button wire:click="sendToParent({{ $report->id }})"
                            class="text-blue-500 hover:underline text-xs">📧 Send to Parent</button>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-gray-500 text-xs">No Daily Reports found.</p>
        @endforelse
    </div>
</div>

</x-filament::page>
