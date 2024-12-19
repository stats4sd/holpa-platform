<x-filament-panels::page>
    <div class="container mx-auto xl:px-12 !mb-4">
        <!-- <div class="surveyblocks py-16 mb-4">
            <div class="text-base px-12">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ac venenatis elit. Vivamus non urna ac turpis hendrerit tincidunt ut eget risus.
                    Curabitur sagittis, ex a consectetur convallis, libero nisi efficitur sapien, non eleifend enim lectus vel leo.
                </p>
            </div>
        </div> -->
        <!-- Tabs -->
        <div class="surveyblocks  pb-24 mb-32 pt-12 px-12">
            <div class="grid grid-cols-3 gap-4   h-max mb-12">
                <div wire:click="setActiveTab('local')" class="{{ $activeTab === 'local' ? 'tabbuttons' : '' }}  rounded-2xl   cursor-pointer lisptabs bg-gray-100 ">
                    <livewire:rounded-square
                        heading="Upload local indicators"
                        description="Upload the local indicators you identified in the LISP workshop."
                    />
                </div>
                <div wire:click="setActiveTab('match')" class="{{ $activeTab === 'match' ? 'tabbuttons' : '' }}  rounded-2xl cursor-pointer lisptabs bg-gray-100">
                    <livewire:rounded-square
                        heading="Match with existing global indicators"
                        description="Browse the list of indicators already available in the HOLPA global survey, and match them to your identified local indicators."
                    />
                </div>
                <div wire:click="setActiveTab('custom')" class="{{ $activeTab === 'custom' ? 'tabbuttons' : '' }}  rounded-2xl cursor-pointer lisptabs bg-gray-100">
                    <livewire:rounded-square
                        heading="Add custom indicators"
                        description="If your local indicators do not already exist in the global survey, you can add them as custom indicators."
                    />
                </div>
            </div>

            <!-- Content -->    
            <div class="px-6">
                @if ($activeTab === 'local')
                    <livewire:upload-local-indicators />
                @elseif ($activeTab === 'match')
                    @include('filament.app.pages.match-indicators')
                @elseif ($activeTab === 'custom')
                    <livewire:custom-indicators />
                    @else
                    <div class="mx-auto w-max">                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6  ">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                            </svg>   <span class="ml-2 font-semibold">Select a tab above to begin </span>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>
