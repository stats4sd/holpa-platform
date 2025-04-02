
<x-filament-panels::page>
<div class="container mx-auto  ">
        <div class="surveyblocks pt-16 pb-24 mb-32 px-12 lg:px-16">

    <p>In the HOLPA Household Survey, there are questions to ask about a specific time frame. By default, this time frame is "<b>In the last 12 months</b>". However, that is not suitable for all situations. For your survey, it might make more sense to ask the questions about "<b>Last Season</b>" or "<b>Last Year</b>".
    </p>
    <p class="mb-12">Below is the complete list of questions that use this time frame. Please review the questions. If you wish to change the time frame for your survey, update the text in the text box below. Your entry will be automatically saved and the question text in your survey form will be updated.</p>

    {{ $this->form }}
<div class="h-12"></div>
    {{ $this->table }}
</div></div>
</x-filament-panels::page>
