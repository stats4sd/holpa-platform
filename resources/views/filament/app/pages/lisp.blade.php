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
        instructions='Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ac venenatis elit. Vivamus non urna ac turpis hendrerit tincidunt ut eget risus. Curabitur sagittis, ex a consectetur convallis, libero nisi efficitur sapien, non eleifend enim lectus vel leo. Morbi tincidunt libero ut nunc scelerisque, eget fringilla nulla volutpat. Aliquam feugiat massa sit amet arcu convallis, et iaculis ligula facilisis. Etiam accumsan magna et ipsum facilisis, at malesuada nulla ornare.'
        videoUrl='https://www.youtube.com/embed/VIDEO_ID'
    />

    <div class="surveyblocks">
        <div class="mb-6 p-16">
            <p class="font-bold text-green text-lg pb-4">CUSTOM SURVEY</p>
            <p><b>You are customising the survey for this project only.</b></p>
            <p>Customisations you make in the following steps will <b>only affect the localised version of the survey used by your team.</b>
                The global survey selected/uploaded in Step 1 and shared with other teams will remain unchanged. Youi will be prompted to update
                the translation of your survey in future steps.</p>
        </div>

        <livewire:offline-action
            heading='Local indicator selection process (LISP) workshop'
            description='Quick description'
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
