<?php
    use App\Filament\App\Pages\SurveyDashboard;
    $surveyDashboardUrl = SurveyDashboard::getUrl();
?>

<x-filament-panels::page class=" px-10 h-full">

    <livewire:page-header-with-instructions
        instructions='Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ac venenatis elit. Vivamus non urna ac turpis hendrerit tincidunt ut eget risus. Curabitur sagittis, ex a consectetur convallis, libero nisi efficitur sapien, non eleifend enim lectus vel leo. Morbi tincidunt libero ut nunc scelerisque, eget fringilla nulla volutpat. Aliquam feugiat massa sit amet arcu convallis, et iaculis ligula facilisis. Etiam accumsan magna et ipsum facilisis, at malesuada nulla ornare.'
        videoUrl='https://www.youtube.com/embed/VIDEO_ID'
        />

    <div class="container mx-auto xl:px-12 ">
        <div class="surveyblocks pr-10 h-full py-12">

          <x-rounded-section
              heading='Customise place-based questionnaire'
              description='Adapt units, crops, and other details mentioned in the form to be locally relevant.'
              buttonLabel='Update'
              :url='\App\Filament\App\Pages\DietDiversity::getUrl()'
              />

            <livewire:offline-action
                heading='Initial Pilot'
                description='Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ac venenatis elit. Vivamus non urna ac turpis hendrerit tincidunt ut eget risus.'
                buttonLabel='View details'
                url='url_to_be_added_here'
                />
        </div>
    </div>

    <!-- Footer with option to mark as complete - funcitonality still to come! -->
    <div class="completebar">
        <a href="{{ $surveyDashboardUrl }}" class="buttonb mx-4 inline-block">Go back</a>
        <a href="" class="buttona mx-4 inline-block ">Mark as completed</a>
    </div>

</x-filament-panels::page>
