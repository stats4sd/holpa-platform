<div class="{{ $expanded ? 'border border-gray-200' : '' }}">
    <div class="py-4 px-8 border border-gray-200 flex justify-between items-center space-x-8 bg-gray-100">
        <div class="w-full flex justify-start">
            <h5 class="w-full md:w-1/2 lg:w-1/4 font-bold text-lg">{{ $localIndicator->name }}</h5>
        </div>
        <div class="self-end">

            <button class="buttona text-nowrap flex items-center justify-between" wire:click="$toggle('expanded')">
                @if($expanded)
                <x-heroicon-o-chevron-up class="h-6 font-bold text-lg pe-4" />Hide Questions
                @else
                <x-heroicon-o-chevron-down class="h-6 font-bold text-lg pe-4" />Show Questions
                @endif
            </button>
        </div>
    </div>


    <div class="p-4 border border-gray-200 transition ease-in-out delay-150 {{ $expanded ? 'visible' : 'hidden' }}">
        {{ $this->table }}
    </div>


    <div class="p-4 border border-gray-200 transition ease-in-out delay-150 {{ $expanded ? 'visible' : 'hidden' }}">
        {{ $this->form }}

        <button wire:click="saveFormData()" class="buttona text-sm">Save Questions</button>
    </div>
</div>