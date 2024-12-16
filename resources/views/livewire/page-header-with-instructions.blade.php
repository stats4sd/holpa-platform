<?php
use App\Filament\App\Pages\SurveyDashboard;
?>

<div>

    <!-- Content -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 surveystepshead">

        <!-- Instructions -->
        <div class="py-4 rounded-md">
            <h2 class="text-green font-extrabold bold text-lg mb-4">Instructions</h2>
            <p class="text-black mb-8">{{ $instructions }}</p>
        </div>

        <!-- Video -->
        <div class="p-4 rounded-md  h-52 w-full justify-content-right">
            <iframe class=" instructionsvid w-96  rounded-md h-52" src="{{ $videoUrl }}" frameborder="0" allowfullscreen></iframe>
        </div>

    </div>

</div>
