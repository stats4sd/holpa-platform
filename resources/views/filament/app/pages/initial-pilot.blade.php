<x-filament-panels::page class="px-10 h-full">

    <livewire:page-header-with-instructions
        instructions1='The localisation sections allow you to adjust the HOLPA survey to ensure it is relevant to the target audience. The HOLPA tool aims to balance harmonisation and comparability between results with specific adaptations to ensure those results are applicable and useful at a local level. '
        instructions2='In this first section, you can customise certain questions and answer options. For example, in different geographical locations, farmers would be growing different crops and different staple foods would be commonly consumed; the options in the questionnaire should reflect this. '
        instructions3='Following customisation, a pilot test should be conducted to check the sense and functionality of the survey. The initial pilot page contains more detailed guidance on this process.'
        instructionsmarkcomplete='you have made all the desired adaptions to the details available for change in this step, piloted the full survey with a local researcher or practitioner, and made any needed adjustments. '
        videoUrl='https://www.youtube.com/embed/VIDEO_ID'
    />

    <div class="container mx-auto xl:px-12">
        <div class="surveyblocks p-10">


        {{ $this->infolist }}
        {{ $this->table }}

        </div>
    </div>
</x-filament-panels::page>
