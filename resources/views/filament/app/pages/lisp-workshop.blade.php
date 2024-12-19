<?php
use App\Filament\App\Pages\Lisp;
?>

<x-filament-panels::page>

    <div class="text-lg">
        <p>The local indicator selection process (LISP) is an essential step to ensure that the variables captured by the HOLPA tool are relevant and approprate to the local context.</p>
        <p class="pt-4">The LISP involves conducting a one-day workshop with local farmers and stakeholders, to brainstorm and prioritize a set of local indicators to include in the HOLPA tool.
            Participants brainstorm potential indicators for each one of the same dimensions as the HOLPA Performance module (agronomic, social, environmental), that could be used
            to monitor the types of changes they want to see in their farms and landscapes. After agreeing on a set of evaluation criteria (such as importance, ease of measurement,
            liklihood of changing), participants evaluate the potential indicators to arrive at a set of approximately three local indicators per performance dimension to include in
            the localised HOLPA tool.
        </p>
        <p class="pt-4">More guidance on how to complete the workshop.</p>
    </div>

   <livewire:rounded-section
        heading='INDICATOR TEMPLATE'
        description='To create the local indicators and incorporate them into the HOLPA survey, <b>you will need to use this template file.</b>
                          This will ensure the required information for the indicators is included, and you will need to use it to upload your indicators
                          to include in your survey.'
        buttonLabel='Download .xlsx'
        url='url_to_be_added_here'
    />

    <div class="h-1 bg-green w-full mb-8"></div>

    <div class="font-bold text-green text-lg mb-4">OPTIONAL WORKSHOP SUPPORTING MATERIALS</div>

    <livewire:rounded-section
        heading='Slideshow Template'
        description='Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ac venenatis elit. Vivamus non urna ac turpis hendrerit tincidunt ut eget risus.'
        buttonLabel='Download .xlsx'
        url='url_to_be_added_here'
    />

    <livewire:rounded-section
        heading='Global Survey Indicators'
        description='Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ac venenatis elit. Vivamus non urna ac turpis hendrerit tincidunt ut eget risus.'
        buttonLabel='Download .xlsx'
        url='url_to_be_added_here'
    />

    <div class="flex justify-center mt-8">
        <a href="url_to_be_added_here"
           class="px-6 py-2 bg-green text-white font-semibold rounded-lg">
            Download all workshop materials .zip
        </a>
    </div>

    <div class="h-1 bg-green w-full mt-8 mb-8"></div>

    <div class="font-bold text-green text-lg mb-4">NEXT STEPS</div>
    <div class="text-gray-800 text-lg leading-relaxed mb-6">
        <p>Following completion of the workshop and identification of the tools, the next step will guide you through the process of mapping your selected
           indicators to available optional indicators, and if necessary uploading new indicators.
        </p>
    </div>

    <div class="flex justify-center space-x-4 mt-6">
        <a href="{{ Lisp::getUrl() }}"
           class="px-6 py-2 bg-green text-white font-semibold rounded-lg text-center">
            Back
        </a>
        <a href="url_to_be_added_here"
           class="px-6 py-2 bg-green text-white font-semibold rounded-lg text-center">
            Mark as Completed
        </a>
    </div>

</x-filament-panels::page>
