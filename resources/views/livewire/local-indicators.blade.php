<div>

    <div class="text-lg font-bold text-green">
        LOCAL INDICATORS
    </div>

    <div class="py-4">
        Select an indicator to start looking for matches.
    </div>

    <div class="space-y-4">
        @forelse ($indicators as $indicator)
            <div class="shadow rounded-lg p-4 cursor-pointer {{ $selectedIndicatorId === $indicator->id ? 'bg-green text-white' : 'bg-white text-green' }}" 
                 wire:click="selectIndicator({{ $indicator->id }})">
                @if ($indicator->global_indicator_id)
                    <div class="flex items-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-lg font-semibold">MATCHED</span>
                    </div>
                @endif
                <p>
                    {{ $indicator->name }}
                </p>
            </div>
        @empty
            <div>No local inicators have been uploaded.</div>
        @endforelse
    </div>

    <div class="flex justify-center gap-4 py-8">
        {{ $this->resetAction }}
    </div>

    <x-filament-actions::modals />

</div>