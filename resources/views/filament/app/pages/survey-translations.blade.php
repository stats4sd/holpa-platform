<?php
    use App\Filament\App\Pages\SurveyDashboard;
    $surveyDashboardUrl = SurveyDashboard::getUrl();
?>

<x-filament-panels::page >

    <livewire:page-header-with-instructions
        instructions1='In this section, you will select the language or languages in which you plan to run the survey and either select an existing translation of the tool or create your own using a provided template.'
        instructions2=''
        instructions3='In the select translation table, choose the target language or languages for your survey. You can download and preview the translation if desired. You can add or remove translations at any point. '
        instructions4='If a suitable translation is not available for your target language, you will need to create one. Add the desired language, and it will appear in the table. Click update to download the translation template, which you will need to complete with translations for all the required fields. Once the translation is ready, return to this page to upload it, and the translation will be available for use. '

        instructionsmarkcomplete=' you have selected and (where needed) uploaded the completed translation for each language you intend to use for the survey. '
        videoUrl='https://www.youtube.com/embed/VIDEO_ID'
         />

    <div id="languages">
        <!-- Main Section -->
        <div class="container   mx-auto xl:px-12 ">
            <div class="surveyblocks px-10 h-full pt-10 pb-16">

                @livewire('team-locales-table')
                @livewire('locales-table')

            </div>

            <!-- Footer -->
            <div class="completebar">
                @if(auth()->user()->latestTeam->languages_complete === 1)
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
