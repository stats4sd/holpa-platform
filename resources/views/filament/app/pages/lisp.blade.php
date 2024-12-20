<?php
    use App\Filament\App\Pages\LispWorkshop;
    use App\Filament\App\Pages\LispIndicators;
    use App\Filament\App\Pages\SurveyDashboard;

    $lispWorkshopUrl = LispWorkshop::getUrl();
    $lispIndicatorsUrl = LispIndicators::getUrl();
    $surveyDashboardUrl = SurveyDashboard::getUrl();
?>

<x-filament-panels::page class="px-12 h-full">

    <livewire:page-header-with-instructions
        instructions1='The local indicator selection process (LISP) is a vital part of the localisation of the HOLPA tool. It involves conducting a workshop with local farmers and stakeholders to identify a set of local indicators to include in the HOLPA tool that could be used to monitor the types of changes they want to see in their farms and landscapes. '
        instructions2='The LISP workshop page provides some guidance and materials to support teams with planning the workshop, but the details of this will be specific to each team and location. There is a template provided to collect the details of the indicators identified by the workshop; this template will be used to add these local indicators on the following page.'
        instructions3='The customise indicators page allows you to incorporate the local indicators identified in your workshop into the customised HOLPA tool by uploading the local indicators, matching them where possible with available HOLPA indicators, and adding new custom indicators for any that remain.'
        instructionsmarkcomplete='you have completed the LISP worksop and added the local indicators indicated by local stakeholders.'
        videoUrl='https://www.youtube.com/embed/VIDEO_ID'
    />

    <div class="container mx-auto xl:px-12 ">
    <div class="surveyblocks pr-10 pb-6">
    <div class="mb-12 -mr-10  px-16 pb-12 text-white bg-green">
        <p class="font-bold text-green text-lg pb-4">CUSTOM SURVEY</p>
        <p><b>You are customising the survey for this project only.</b></p>
        <p>Customisations you make in the following steps will <b>only affect the localised version of the survey used by your team.</b>
            The global survey selected/uploaded in Step 1 and shared with other teams will remain unchanged. Youi will be prompted to update
            the translation of your survey in future steps.</p>
    </div>

        <livewire:offline-action
            heading='Local indicator selection process (LISP) workshop'
            description='Guidance and materials to support teams with planning the workshop, including a template to collect the details of the indicators identified by the workshop; this template will be used to add these local indicators on the following page.'
            buttonLabel='View details'
            :url='$lispWorkshopUrl'
        />

        <x-rounded-section
            heading='Customise indicators'
            description='Customise the indicators included in your survey based on the outcome of the LISP workshop. This includes options to map
                                indicators identified during the workshop to existing available indicators as well as adding custom indicators and
                                questions.'
            buttonLabel='Update'
            :url='$lispIndicatorsUrl'
        />

    </div>

    <!-- Footer -->
    <div class="completebar">
        @if(auth()->user()->latestTeam->lisp_progress === 'complete')
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
