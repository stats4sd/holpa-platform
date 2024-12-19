<?php
    use App\Filament\App\Pages\SurveyDashboard;
    $surveyDashboardUrl = SurveyDashboard::getUrl();
?>

<x-filament-panels::page class="h-full">

    <livewire:page-header-with-instructions
        instructions='Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ac venenatis elit. Vivamus non urna ac turpis hendrerit tincidunt ut eget risus. Curabitur sagittis, ex a consectetur convallis, libero nisi efficitur sapien, non eleifend enim lectus vel leo. Morbi tincidunt libero ut nunc scelerisque, eget fringilla nulla volutpat. Aliquam feugiat massa sit amet arcu convallis, et iaculis ligula facilisis. Etiam accumsan magna et ipsum facilisis, at malesuada nulla ornare.'
        video-url='https://www.youtube.com/embed/VIDEO_ID'
    />

    <div class="container mx-auto xl:px-12 ">
        <div class="surveyblocks pr-10 h-full pt-8">

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
        <a href="{{ $surveyDashboardUrl }}" class="buttonb mx-4 inline-block">Go back</a>
        {{ $this->markCompleteAction }}
    </div>

</x-filament-panels::page>
