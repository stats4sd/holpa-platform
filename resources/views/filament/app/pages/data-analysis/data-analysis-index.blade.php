<?php

use App\Filament\App\Pages\SurveyDashboard;

$surveyDashboardUrl = SurveyDashboard::getUrl();
?>

<x-filament-panels::page class="h-full">

<x-instructions-sidebar>
        <x-slot:heading>Instructions</x-slot:heading>
        <x-slot:instructions>

            <div class="mx-12 mb-4">
                <p class="mb-2">                
Download data instructions                </p>

                  
        </div>
    </x-slot:instructions>
</x-instructions-sidebar>



    <div class="container mx-auto  ">
        <div class="surveyblocks pt-16 pb-24 mb-32 px-12 lg:px-16">
            <h3 class="mb-6">Download data for analysis </h3>
            <p class="mb-4">
                Here, you can download the complete dataset for your survey. The download will be an .xlsx file containing:
            </p>
            <ul class="list-disc ml-6">
                <li class="mb-1">
                    All the data from the live data collection.
                </li>
                <li class="mb-1">
                    Agroecology and performance indicators, automatically calculated at farm level.
                </li>
                <li class="mb-4">
                    A detailed data dictionary to help you navigate through the data.
                </li>
            </ul>
            <p class="mb-8">
                Once you have downloaded the data, you can conduct data analysis as required, and it is up to your team or organisation to manage storage and sharing of the dataset as appropriate. Download the complete dataset from your survey. This export includes the agro-ecology and performance indicators, automatically calculated at farm-level, along with a detailed data dictionary to help you navigate through the data.
            </p>
{{-- This was the previous export data button - I wanted to make it look like the other downloads from other sections (I think just the workshop section) but couldn't figure out how to make that work so ....--}}
                 {{-- {{ $this->exportDataAction }} --}}

                <x-download-section :url="url('#')">
                    <x-slot:heading>Survey Dataset</x-slot:heading>
                        <x-slot:description>Download the complete dataset from your survey. This export includes calculated agro-ecology and performance indicators and a detailed data dictionary.</x-slot:description>
                        <x-slot:buttonLabel>Download .xlsx</x-slot:buttonLabel>
                </x-download-section>
       

        </div>
    </div>

    <!-- Footer -->
    <div class="completebar">
        @if(auth()->user()->latestTeam->data_collection_progress === 'complete')
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