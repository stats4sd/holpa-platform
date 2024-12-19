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

    <!-- Footer with option to mark as complete - funcitonality still to come! -->
    <div class="completebar">
        <a href="{{ $surveyDashboardUrl }}" class="buttonb mx-4 inline-block">Go back</a>
        <a href="" class="buttona mx-4 inline-block ">Mark as completed</a>
    </div>
</x-filament-panels::page>
