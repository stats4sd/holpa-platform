<?php
    use App\Filament\App\Pages\SurveyDashboard;
    $surveyDashboardUrl = SurveyDashboard::getUrl();
?>

<x-filament-panels::page >

    <livewire:page-header-with-instructions
        instructions='Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ac venenatis elit. Vivamus non urna ac turpis hendrerit tincidunt ut eget risus. Curabitur sagittis, ex a consectetur convallis, libero nisi efficitur sapien, non eleifend enim lectus vel leo. Morbi tincidunt libero ut nunc scelerisque, eget fringilla nulla volutpat. Aliquam feugiat massa sit amet arcu convallis, et iaculis ligula facilisis. Etiam accumsan magna et ipsum facilisis, at malesuada nulla ornare.'
        videoUrl='https://www.youtube.com/embed/VIDEO_ID'
         />

    <div id="languages">
        <!-- Main Section -->
        <div class="container mx-auto xl:px-12 ">
            <div class="surveyblocks px-10 h-full py-12">

                @livewire('team-locales-table')
                @livewire('locales-table')

            </div>

            <!-- Footer -->
            <div class="completebar">
                @if(auth()->user()->latestTeam->languages_progress === 'complete')
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

        </div>
    </div>

</x-filament-panels::page>
