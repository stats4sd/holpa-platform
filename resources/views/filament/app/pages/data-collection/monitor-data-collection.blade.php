<x-filament-panels::page>
    <x-instructions-sidebar>
        <x-slot:heading>Monitor Data Collection Progress</x-slot:heading>

        <x-slot:instructions>
            <p>
                This page will show you the progress of the data collection process.
            </p>
            TODO: add more instructions here
        </x-slot:instructions>
    </x-instructions-sidebar>

    @if ($team->locationLevels->count() === 0)
        <div class="bg-red-500 text-white p-4">
            It looks like your team has not yet added any locations to conduct the survey. Please make sure you do so before proceeding.
        </div>
    @else

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <x-filament::section heading="Data Collected">
                Household Submissions: {NUMBER}<br/>
                Fieldwork Submissions: {NUMBER}<br/>
            </x-filament::section>

            <x-filament::section heading="Summary">
                Farms Fully Surveyed: {NUMBER} / {TOTAL}<br/>
                Non-consenting Farms: {NUMBER}
            </x-filament::section>

        </div>
        <h2>Submissions By Location</h2>
        <x-filament::tabs>
            @foreach ($team->locationLevels as $level)
                <x-filament::tabs.item :active="$level->id === $this->locationLevelId" wire:click="$set('locationLevelId', {{$level->id}})" :key="'location-level-'.$level->id.'-tab'" class="cursor-pointer">
                    {{ \Illuminate\Support\Str::plural($level->name) }}
                </x-filament::tabs.item>
            @endforeach

            <x-filament::tabs.item :active="!$locationLevelId" wire:click="$set('locationLevelId', null)" :key="'location-level-null-tab'" class="cursor-pointer">
                Submissions
            </x-filament::tabs.item>
        </x-filament::tabs>

        @if($locationLevelId)
            <livewire:data-collection.data-collection-by-location :location-level-id="$locationLevelId"/>
        @else
            <livewire:data-collection.data-collection-by-submission/>
        @endif

    @endif

</x-filament-panels::page>
