<?php

use App\Filament\App\Pages\SurveyDashboard;

$surveyDashboardUrl = SurveyDashboard::getUrl();
?>

<x-filament-panels::page class=" px-10 h-full">

{{--        instructions1='The localisation sections allow you to adjust the HOLPA survey to ensure it is relevant to the target audience. The HOLPA tool aims to balance harmonisation and comparability between results with specific adaptations to ensure those results are applicable and useful at a local level. '--}}
{{--        instructions2='In this first section, you can customise certain questions and answer options. For example, in different geographical locations, farmers would be growing different crops and different staple foods would be commonly consumed; the options in the questionnaire should reflect this. '--}}
{{--        instructions3='Following customisation, a pilot test should be conducted to check the sense and functionality of the survey. The initial pilot page contains more detailed guidance on this process.'--}}
{{--        instructionsmarkcomplete='you have made all the desired adaptions to the details available for change in this step, piloted the full survey with a local researcher or practitioner, and made any needed adjustments. '--}}
{{--        videoUrl='https://www.youtube.com/embed/VIDEO_ID'--}}

    <div class="container mx-auto xl:px-12 ">
        <div class="surveyblocks pr-10  ">
            <div class="mb-12 -mr-10  px-16 pb-12 text-white bg-green">
                <p class="font-bold text-green text-lg pb-4">CUSTOM SURVEY</p>
                <p>
                    <b>You are customising the survey for this project only.</b>
                </p>
                <p>Customisations you make in the following steps will
                    <b>only affect the localised version of the survey used by your team.</b>
                    The global survey selected/uploaded in Step 1 and shared with other teams will remain unchanged. Youi will be prompted to update
                    the translation of your survey in future steps.
                </p>
            </div>
            <x-rounded-section
                heading='Customise Questionnaire - Adapt time frame'
                buttonLabel='Update'
                :url='\App\Filament\App\Pages\PlaceAdaptations\TimeFrame::getUrl()'>
                <x-slot:description>Some questions in the Household Survey ask about a specific time frame in the recent past, for example "In the last 12 months, has any household member received training in *** topic?". By default, this time frame is
                    "In the last 12 months". You may customise this to your specific requirements.
                </x-slot:description>
            </x-rounded-section>

            <x-rounded-section
                heading='Customise Qustionnaire - Adapt Diet Quality Module'
                buttonLabel='Update'
                :url='\App\Filament\App\Pages\PlaceAdaptations\DietDiversity::getUrl()'/>
            <x-slot:description>HOLPA uses an international standard "Diet Quality" module. This module is available for over 100 countries. We recommend you select the version most suited to your context.
            </x-slot:description>

            <x-rounded-section
                heading='Customise place-based questionnaire'
                description='Adapt units, crops, and other choice list entries to be locally relevant..'
                buttonLabel='Update'
                :url='\App\Filament\App\Clusters\Localisations::getUrl()'/>
            <x-offline-action-section
                heading='Initial Pilot'
                description='Initial piloting should be conducted to check the sense and functionality of the survey.'
                buttonLabel='View details'
                :url="\App\Filament\App\Pages\PlaceAdaptations\InitialPilot::getUrl()"/>
        </div>
    </div>

    <!-- Footer -->
    <div class="completebar">
        @if(auth()->user()->latestTeam->pba_complete === 1)
            <div class="mb-6 mx-auto md:mr-24 md:ml-0 md:inline-block block text-green ">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 inline " fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
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
