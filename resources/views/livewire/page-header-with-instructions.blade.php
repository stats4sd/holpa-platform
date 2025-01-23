<?php
use App\Filament\App\Pages\SurveyDashboard;
?>

<div>

    <!-- Content -->
    <div class="grid grid-cols-1 h-max xl:grid-cols-4 gap-4 mb-6 surveystepshead">

        <!-- Instructions -->
        <div class="py-4 lg:col-span-3 gap-8 flex-grow rounded-md pr-8">
            <h2 class="text-green mb-4">Instructions</h2>
            <p class="text-black mb-2">{!! $instructions1 !!}</p>
            <p class="text-black mb-2">{!! $instructions2 !!}</p>
            <p class="text-black mb-2">{!! $instructions3 !!}</p>
            <p class="text-black mb-2">{!! $instructions4 !!}</p>
            <p class="text-black mb-2">{!! $instructions5 !!}</p>
            <p class="text-black mb-8">{!! $instructions6 !!}</p>
            @if($instructionsmarkcomplete)
            <p class="text-black mb-8"> <span class="font-semibold ">Mark this section as complete when: </span>
                {{ $instructionsmarkcomplete }}</p>
            @endif
        </div>

        <!-- Video -->
        <div class="p-4 rounded-md content-center flex h-52 lg:mt-12 w-full justify-content-right">
            <iframe class=" instructionsvid w-96 mx-auto rounded-md h-52" src="{{ $videoUrl }}" frameborder="0" allowfullscreen></iframe>
        </div>

    </div>

</div>
