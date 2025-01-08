<?php
    use App\Filament\App\Pages\SurveyDashboard;
    $surveyDashboardUrl = SurveyDashboard::getUrl();
?>

<x-filament-panels::page >

    <livewire:page-header-with-instructions
        instructions1='TK'
        instructionsmarkcomplete=' you have selected and (where needed) uploaded the completed translation for each language you intend to use for the survey. '
        videoUrl='https://www.youtube.com/embed/VIDEO_ID'
         />

    <div id="languages">
        <!-- Main Section -->
        <div class="container   mx-auto xl:px-12 ">
            <div class="surveyblocks px-10 h-full pt-10 pb-16">

                @livewire('team-languages-table')

            </div>

        </div>
    </div>

</x-filament-panels::page>
