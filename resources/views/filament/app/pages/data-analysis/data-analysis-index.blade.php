<?php
    use App\Filament\App\Pages\SurveyDashboard;
    $surveyDashboardUrl = SurveyDashboard::getUrl();
?>

<x-filament-panels::page class="h-full">
<div class="hidden">
    <div class="container mx-auto xl:px-12 ">
        <div class="surveyblocks pr-10  pt-8">

            <x-rounded-section
                url='url_to_be_added_here'
            >
                <x-slot:heading>Download Data and Calculated Indicators</x-slot:heading>
                <x-slot:description>Download the complete dataset from your survey. This export includes the agro-ecology and performance indicators, automatically calculated at farm-level, along with a detailed data dictionary to help you navigate through the data.</x-slot:description>

                <x-slot:actionButton> {{ $this->exportDataAction }} </x-slot:actionButton>
            </x-rounded-section>

        </div>
    </div>

    <!-- Footer -->
    <div class="completebar">
        @if(auth()->user()->latestTeam->data_analysis_progress === 'complete')
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
