<?php
use App\Filament\App\Pages\SurveyDashboard;
?>

<div class="pt-8 px-4">

    <!-- Content -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">

        <!-- Instructions -->
        <div class="py-4 rounded-md">
            <h2 class="text-green font-bold text-lg mb-4">Instructions</h2>
            <p class="text-black mb-16">{{ $instructions }}</p>
        </div>

        <!-- Video -->
        <div class="p-4 rounded-md">
            <iframe class="w-full h-80 rounded-md" src="{{ $videoUrl }}" frameborder="0" allowfullscreen></iframe>
        </div>

    </div>
</div>
