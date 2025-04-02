<?php

use App\Filament\App\Pages\SurveyDashboard;

$surveyDashboardUrl = SurveyDashboard::getUrl();
?>

<x-filament-panels::page class="h-full">


{{--        instructions1='This section aims to both allow for quality control of the customised HOLPA survey and training of enumerators.'--}}
{{--        instructions2='The pilot and enumerator training section contains details of this process and lets you preview the survey to test it. You will need to conduct this process offline, and will likely then need to return to previous sections to make additional edits to the survey. There are links in this section, or you can revisit any section from the dashboard as needed.'--}}
{{--        instructionsmarkcomplete='once you have conducted the pilot and training, and are satisfied that the required adjustments have been made. '--}}
{{--        video-url='https://www.youtube.com/embed/VIDEO_ID'--}}
{{--    --}}
    <div class="container mx-auto xl:px-12 ">
        <div class="surveyblocks pr-10  ">
            <div class="mb-12 -mr-10  px-16 pb-12 text-white bg-green">
                <p class="font-bold text-green text-lg pb-4">CUSTOM SURVEY</p>
                <p>
                    <b>You are customising the survey for this project only.</b>
                </p>
                <p>Customisations you make in the following steps will
                    <b>only affect the localised version of the survey used by your team.</b>
                    The global survey selected/uploaded in Step 1 and shared with other teams will remain unchanged. You will be prompted to update
                    the translation of your survey in future steps.
                </p>
            </div>
            <x-offline-action :url="\App\Filament\App\Pages\Pilot\MainPilot::getUrl()">
                <x-slot:heading>Pilot and enumerator training</x-slot:heading>
                <x-slot:description>Once the local indicators have been included, the HOLPA tool is ready to be piloted by the enumerators with local farmers.</x-slot:description>
                <x-slot:buttonLabel>View details</x-slot:buttonLabel>
            </x-offline-action>
        </div>
    </div>

    <!-- Footer -->
    <div class="completebar">
        @if(auth()->user()->latestTeam->pilot_progress === 'complete')
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
