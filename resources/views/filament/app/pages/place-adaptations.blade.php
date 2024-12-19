<x-filament-panels::page class=" px-10 h-full">

    <livewire:page-header-with-instructions
        instructions='Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ac venenatis elit. Vivamus non urna ac turpis hendrerit tincidunt ut eget risus. Curabitur sagittis, ex a consectetur convallis, libero nisi efficitur sapien, non eleifend enim lectus vel leo. Morbi tincidunt libero ut nunc scelerisque, eget fringilla nulla volutpat. Aliquam feugiat massa sit amet arcu convallis, et iaculis ligula facilisis. Etiam accumsan magna et ipsum facilisis, at malesuada nulla ornare.'
        videoUrl='https://www.youtube.com/embed/VIDEO_ID'
    />
    <div class="container mx-auto xl:px-12 ">
        <div class="surveyblocks pr-10 h-full py-12">
            <x-rounded-section
                heading='Customise Questionnaire - Adapt time frame'
                buttonLabel='Update'
                :url='\App\Filament\App\Pages\TimeFrame::getUrl()'
            >
                <x-slot:description>Some questions in the Household Survey ask about a specific time frame in the recent past, for example "In the last 12 months, has any household member received training in *** topic?". By default, this time frame is
                    <b>"In the last 12 months"</b>. You may customise this to your specific requirements.
                </x-slot:description>
            </x-rounded-section>

            <x-rounded-section
                heading='Customise Qustionnaire - Adapt Diet Quality Module'
                buttonLabel='Update'
                :url='\App\Filament\App\Pages\DietDiversity::getUrl()'
            />
            <x-slot:description>HOLPA uses an international standard "Diet Quality" module. This module is available for over 100 countries. We recommend you select the version most suited to your context.</x-slot:description>
            <x-rounded-section
                heading='Customise place-based questionnaire'
                description='Adapt units, crops, and other choice list entries to be locally relevant..'
                buttonLabel='Update'
                :url='\App\Filament\App\Clusters\Localisations::getUrl()'
            />
            <livewire:offline-action
                heading='Initial Pilot'
                description='Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ac venenatis elit. Vivamus non urna ac turpis hendrerit tincidunt ut eget risus.'
                buttonLabel='View details'
                url='url_to_be_added_here'
            />
        </div>
    </div>

    <!-- Footer -->
    <div class="completebar">
        @if(auth()->user()->latestTeam->pba_progress === 'complete')
            <div class="mb-6 mx-auto md:mr-24 md:ml-0 md:inline-block block text-green ">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 inline " fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span class="ml-1 inline text-sm font-bold">SECTION COMPLETE </span>
            </div>
            <a href="{{ $surveyDashboardUrl }}" class="buttonb block max-w-sm mx-auto md:mx-4 md:inline-block mb-6 md:mb-0">Go back</a>
            {{ $this->markIncompleteAction }}
        @else
            <a href="{{ $surveyDashboardUrl }}" class="buttonb mx-4 inline-block">Go back</a>
            {{ $this->markCompleteAction }}
        @endif
    </div>
</x-filament-panels::page>
