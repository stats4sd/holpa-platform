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

    <!-- Footer with option to mark as complete - funcitonality still to come! -->
    <div class="completebar">
        <a href="{{ $surveyDashboardUrl }}" class="buttonb mx-4 inline-block">Go back</a>
        <a href="" class="buttona mx-4 inline-block ">Mark as completed</a>
    </div>

</x-filament-panels::page>
