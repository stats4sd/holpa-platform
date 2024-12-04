<x-filament-panels::page>

    <div class="text-lg">
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ac venenatis elit. Vivamus non urna ac turpis hendrerit tincidunt ut eget risus.
            Curabitur sagittis, ex a consectetur convallis, libero nisi efficitur sapien, non eleifend enim lectus vel leo.
        </p>
    </div>

    <!-- Tabs -->
    <div class="grid grid-cols-3 gap-6">
        <div wire:click="setActiveTab('local')" class="{{ $activeTab === 'local' ? 'bg-gray-200' : '' }} rounded-3xl">
            <livewire:rounded-square
                heading="UPLOAD LOCAL INDICATORS"
                description="Upload the local indicators you identified in the LISP workshop."
            />
        </div>
        <div wire:click="setActiveTab('match')" class="{{ $activeTab === 'match' ? 'bg-gray-200' : '' }} rounded-3xl">
            <livewire:rounded-square
                heading="MATCH WITH EXISTING GLOBAL INDICATORS"
                description="Browse the list of indicators already available in the HOLPA global survey, and match them to your identified local indicators."
            />
        </div>
        <div wire:click="setActiveTab('custom')" class="{{ $activeTab === 'custom' ? 'bg-gray-200' : '' }} rounded-3xl">
            <livewire:rounded-square
                heading="ADD CUSTOM INDICATORS"
                description="If your local indicators do not already exist in the global survey, you can add them as custom indicators."
            />
        </div>
    </div>

    <!-- Content -->    
     <div>
        @if ($activeTab === 'local')
            <livewire:upload-local-indicators />
        @elseif ($activeTab === 'match')
            @include('filament.app.pages.match-indicators')
        @elseif ($activeTab === 'custom')
            <livewire:custom-indicators />
        @endif
    </div>

</x-filament-panels::page>
