<x-filament-panels::page>
<div class="container mx-auto xl:px-12 ">
    <div class="surveyblocks pt-16 pb-24 mb-32">
    <div class="text-base px-12">
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ac venenatis elit. Vivamus non urna ac turpis hendrerit tincidunt ut eget risus.
            Curabitur sagittis, ex a consectetur convallis, libero nisi efficitur sapien, non eleifend enim lectus vel leo.
        </p>
    </div>

    <!-- Tabs -->
    <div class="grid grid-cols-3 gap-6 px-12 mt-12 h-max mb-12">
        <div wire:click="setActiveTab('local')" class="{{ $activeTab === 'local' ? 'bg-gray-200' : '' }} rounded-3xl cursor-pointer">
            <livewire:rounded-square
                heading="UPLOAD LOCAL INDICATORS"
                description="Upload the local indicators you identified in the LISP workshop."
            />
        </div>
        <div wire:click="setActiveTab('match')" class="{{ $activeTab === 'match' ? 'bg-gray-200' : '' }} rounded-3xl cursor-pointer">
            <livewire:rounded-square
                heading="MATCH WITH EXISTING GLOBAL INDICATORS"
                description="Browse the list of indicators already available in the HOLPA global survey, and match them to your identified local indicators."
            />
        </div>
        <div wire:click="setActiveTab('custom')" class="{{ $activeTab === 'custom' ? 'bg-gray-200' : '' }} rounded-3xl cursor-pointer">
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
</div>
</div>
    <!-- Footer with option to mark as complete - funcitonality still to come! -->
    <div class="completebar">
        <a href="" class="buttonb mx-4 inline-block">Go back</a>
        <a href="" class="buttona mx-4 inline-block ">Mark as completed</a>
    </div>


</x-filament-panels::page>
