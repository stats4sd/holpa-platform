<?php

use App\Filament\App\Pages\SurveyDashboard;

$surveyDashboardUrl = SurveyDashboard::getUrl();
?>

<x-filament-panels::page class="h-full">

    <livewire:page-header-with-instructions
        instructions1='The first step is to select the country and languages for your HOLPA implementation. This is important information that will help you contextualise the survey and results.'
        instructionsmarkcomplete='When you have selected the languages for your survey, you should review the available translations. HOLPA is available in multiple languages. The Survey Translation page will show you if there are translations available in your chosen languages. There, you can review the available translations, and upload new translations if required.'
        videoUrl='https://www.youtube.com/embed/VIDEO_ID'
    />

    <div class="container mx-auto xl:px-12 ">
        <div class="surveyblocks pr-10 pt-8">

            <x-rounded-section
                :url="\App\Filament\App\Pages\SurveyCountry::getUrl()"
            >
                <x-slot:heading>Select Country and Languages</x-slot:heading>
                <x-slot:description>Pick the country you will be conducting the survey in, and the languages you will want to use. You can select multiple languages.</x-slot:description>
                <x-slot:buttonLabel>Select Country and Languages</x-slot:buttonLabel>
            </x-rounded-section>

            <x-rounded-section
                :url="\App\Filament\App\Pages\SurveyTranslations::getUrl()"
                >
                <x-slot:heading>Review Translations</x-slot:heading>
                <x-slot:description>Review the available translations for your survey. You can upload new translations if required.</x-slot:description>
                <x-slot:buttonLabel>Review Translations</x-slot:buttonLabel>
            </x-rounded-section>
        </div>
    </div>

    <!-- Footer -->
    <div class="completebar">
        @if(auth()->user()->latestTeam->languages_complete === 1)
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
