<x-filament::page>
    <h2 class="text-xl font-bold mb-6">Upcoming Schedule</h2>

    @if($sessions->isEmpty())
        <p class="text-gray-500">No upcoming sessions.</p>
    @else
        <div class="grid gap-4">
            @foreach($sessions as $session)
                <x-filament::card>
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                {{ $session->child->fullName ?? 'Unnamed Child' }}
                            </h3>
                            <p class="text-sm text-gray-500">
                                {{ $session->slot_date ? $session->slot_date->format('d M Y') : 'No Date' }}
                                • 
                                {{ $session->slot_time ?? 'No Time' }}
                            </p>


                        </div>
                        <span class="px-3 py-1 rounded-full text-xs 
                            {{ $session->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                            {{ ucfirst($session->status) }}
                        </span>
                    </div>
                </x-filament::card>
            @endforeach
        </div>
    @endif
</x-filament::page>
