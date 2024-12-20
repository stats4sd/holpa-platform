<?php
    use App\Filament\App\Pages\SurveyDashboard;
    use App\Filament\App\Clusters\LocationLevels\Resources\FarmResource;

    $farmUrl = FarmResource::getUrl();
    $surveyDashboardUrl = SurveyDashboard::getUrl();
?>

<x-filament-panels::page class="h-full">

    <livewire:page-header-with-instructions
        instructions1='To enable enumerators to conduct the survey, and possibly for data analysis later on, you will need to add the details of the farms you will visit, including the details of the different location levels. For example, a farm might be located in a village, which is in a district, which is in a province, so you would need to add the location levels province > district > village. Then you can add or import the full list of locations at each level and of the farms. '
        instructions2='On the location levels page, you first need to add the names of the different levels. As you add each one, you can indicate if it is a sub-level in the hierarchy (eg a district is within a province), and whether there are farms at that level.
Once you have added levels, you can select them to view a list of the locations that have been added at that level and add locations by importing them from an excel file. '
        instructions3='On the Farms page, you can add farms, either manually or by importing a spreadsheet.'
        instructionsmarkcomplete='you have dded all the location levels, locations, and a full list of the farms where you will conduct the survey.'
        videoUrl='https://www.youtube.com/embed/VIDEO_ID'
    />

    <div class="container mx-auto xl:px-12 ">
        <div class="surveyblocks pr-10 pt-8">

            <x-rounded-section
                heading='Manage location levels'
                description='Manage the location levels (or other strata) in your sampling frame.'
                buttonLabel='Update'
                url='location-levels'
            />

            <x-rounded-section
                heading='List of farms'
                description='Add or import details of the farms you will visit to give the questionnaire.'
                buttonLabel='Update'
                :url='$farmUrl'
            />

        </div>
    </div>

    <!-- Footer -->
    <div class="completebar">
        @if(auth()->user()->latestTeam->sampling_progress === 'complete')
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
