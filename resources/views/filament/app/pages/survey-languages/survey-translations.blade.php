<?php

use App\Filament\App\Pages\SurveyDashboard;

$surveyDashboardUrl = SurveyDashboard::getUrl();
?>

<x-filament-panels::page>


{{--        instructions1='TK'--}}
{{--        instructionsmarkcomplete=' you have selected and (where needed) uploaded the completed translation for each language you intend to use for the survey. '--}}
{{--        videoUrl='https://www.youtube.com/embed/VIDEO_ID'--}}


    <div id="languages">
        <!-- Main Section -->
        <div class=" container mx-auto xl:px-12 ">
            <div class="surveyblocks px-10 h-full pt-10 pb-16">

                <div class="mb-8 translations-tables">
                    <div class="pb-4">
                        <h3 class=" mb-4">Survey Languages</h3>
                    </div>

                    @foreach($languages as $language)
                        <livewire:team-translation-entry :language="$language" :key="$language->id" :team="$team"/>
                    @endforeach

                </div>
            </div>
        </div>
    </div>

</x-filament-panels::page>
