<x-filament::page>
    <x-request.header :requestCase="$requestCase" />
    <x-request.tabs :recordId="$requestCase->id" />

    <div class="w-full p-4 md:p-8">
        @php
        
            $createdAt = optional($requestCase?->child?->parent?->created_at)?->format('F d, Y \a\t h:i A');
            $updatedAt = optional($requestCase?->updated_at)?->format('F d, Y \a\t h:i A');
            $status = $requestCase?->status;

            $statusFormatted = match($status) {
                'new_request'   => 'New Request',
                'under_review'  => 'Under Review',
                'approved'      => 'Approved',
                'assigned'      => 'Assigned',
                'completed'     => 'Completed',
                'cancelled'     => 'Cancelled',
                default         => ucfirst($status ?? 'Unknown'),
            };

            $statusDesc = "Moved to {$statusFormatted} status";
            $updatedBy = $requestCase?->status_updated_by ?? 'Unknown';

            $timeline = [
                [
                    'title' => 'Request submitted',
                    'timestamp' => $createdAt ?? 'N/A',
                    'description' => 'Parent completed registration and child profile',
                    'by' => 'System',
                    'icon' => 'document-arrow-up',
                    'color' => 'text-primary-600',
                ],
                [
                    'title' => 'Payment confirmed',
                    'timestamp' => 'January 15, 2024 at 09:15 AM',
                    'description' => 'Payment of 870 EGP processed successfully',
                    'by' => 'System',
                    'icon' => 'currency-pound',
                    'color' => 'text-green-600',
                ],
                [
                    'title' => 'Initial review',
                    'timestamp' => 'January 15, 2024 at 10:30 AM',
                    'description' => 'Request assigned to review team',
                    'by' => 'Admin User',
                    'icon' => 'eye',
                    'color' => 'text-blue-600',
                ],
                [
                    'title' => 'Status updated',
                    'timestamp' => $updatedAt ?? 'N/A',
                    'description' => $statusDesc,
                    'by' => $updatedBy,
                    'icon' => 'arrow-path',
                    'color' => 'text-orange-500',
                ],
            ];
        @endphp

        <div class="space-y-6">
            @foreach ($timeline as $event)
                <div class="flex gap-4 items-start">
                    <div class="flex-shrink-0 mt-1">
                        @switch($event['icon'])
                            @case('document-arrow-up')
                                <x-heroicon-o-document-arrow-up class="w-6 h-6 {{ $event['color'] }}" />
                                @break
                            @case('currency-pound')
                                <x-heroicon-o-currency-pound class="w-6 h-6 {{ $event['color'] }}" />
                                @break
                            @case('eye')
                                <x-heroicon-o-eye class="w-6 h-6 {{ $event['color'] }}" />
                                @break
                            @case('arrow-path')
                                <x-heroicon-o-arrow-path class="w-6 h-6 {{ $event['color'] }}" />
                                @break
                        @endswitch
                    </div>

                    {{-- المحتوى --}}
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $event['title'] }}</h3>
                            <span class="text-sm text-gray-500 whitespace-nowrap">{{ $event['timestamp'] }}</span>
                        </div>
                        <p class="text-gray-700 mb-1">{{ $event['description'] }}</p>
                        <p class="text-sm text-gray-500 italic">By: {{ $event['by'] }}</p>
                    </div>
                </div>

                @if (!$loop->last)
                    <div class="border-t border-gray-200 my-4"></div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- <x-request.sidebar :requestCase="$requestCase" :acceptedEmployees="$this->acceptedEmployees ?? collect()" /> --}}
    </x-filament::page>
