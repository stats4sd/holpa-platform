<?php
    use App\Filament\App\Pages\SurveyDashboard;
    $surveyDashboardUrl = SurveyDashboard::getUrl();
?>

<x-filament-panels::page class="h-full">
    <x-instructions-sidebar>
        <x-slot:heading>Instructions</x-slot:heading>
        <x-slot:instructions>

            <div class="mx-12 mb-4">

 
                <p class="my-2">
                    Once you have tested and finalised the details of your localised HOLPA survey, data collection may begin. 
                </p>
                    <h5>Set up the survey </h5>

                <p class="my-2">
                Up until this point, all the forms used for pilot testing have been labelled as "test" forms, and the submissions are stored as test data, not to be included in the results. To begin actual data collection, you need to use the "Set up live forms for data collection" section to set your survey to "live". There are some notes on this page to prompt you to double check all the necessary tasks have been completed. Read through these, and when you are ready, click the button to make your survey live. 
                </p>

                 <p class="my-2">
                Once your survey is live, you will be able to use a QR code to set up new devices with the correct forms. Enumerators who have already joined the project using the QR code at the pilot phase can alternatively sync their devices to receive the updated forms.
{{-- Check the above text. --}}
                 <p class="my-2">
                Before enumerators commence data collection, they should double check that the forms on their ODK Collect app are indeed the live versions; test versions will be labelled as such in the form title, e.g. "HOLPA Household Form - Local Shared Test Version". Reminder: under no circumstances should enumerators use the draft versions of the survey forms accessed from the initial pilot section. The data from these is not saved and will be lost. 
                </p>
                 <p class="my-2">
                 Once enumerators begin data collection, you will be able to see form submissions in the next section, Monitor data collection. 
              
{{-- (Not sure if we actually need this section - obviously forms and submissions are on the next step.) --}}
                At the bottom of the page, you can see the titles and published status of your forms. There are options to publish changes if this still needs to be done before commencing data collection.
                </p>


                <h5>Monitor data collection</h5>
                <p class="my-2">

                This page lets you see incoming data. You can track progress and review submissions for quality assurance purposes.
                </p>
                <p class="my-2">
                At the top of the page, you will see a general summary of the data that has been collected; this includes the number of submissions for each form and number of farms surveyed.
                Beneath that, you can browse the submissions. Use the tabs to view submissions by locations at different levels, or  to simply view all of them. 
                </p>
                <p class="my-2">
                There is also an option to download the raw data from submissions. Note that this will be unprocessed, and will not include calculated indicators; to obtain survey data ready for analysis, use the "data analysis" section.
                </p>
                <p class="my-2">
                If you find you need to correct an error in the data, you can directly edit a submission. This should be used sparingly, only where it has been confirmed with an enumerator that something was inputted incorrectly. 

                </p>

                <h5>Mark this section as complete when:</h5>

                <p class="my-2">
                Data collection has been carried out and concluded.
                </p>
            </div>
        </x-slot:instructions>
    </x-instructions-sidebar>
<div class="hidden">

    </div>
    <div class="container mx-auto xl:px-12 ">
        <div class="surveyblocks pr-10  pt-8">

            <x-rounded-section
                heading='Set up the survey'
                description='Manage the ODK survey to be used for data collection'
                buttonLabel='Update'
                :url='\App\Filament\App\Pages\DataCollection\SetUpSurvey::getUrl()'
            />

            <x-rounded-section
                heading='Monitor Data Collection'
                description='Review and quality-check incoming data'
                buttonLabel='Update'
                :url='\App\Filament\App\Pages\DataCollection\MonitorDataCollection::getUrl()'
            />

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
