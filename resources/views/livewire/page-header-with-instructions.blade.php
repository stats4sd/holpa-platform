<?php
use App\Filament\App\Pages\SurveyDashboard;
?>

<div class="bg-white shadow-md pt-8 px-4">

    <!-- Breadcrumbs -->
    <div class="mb-4">
        <a href="{{ url(\App\Filament\App\Pages\SurveyDashboard::getUrl()) }}" class="hover:underline">Survey Dashboard</a> > {{ $heading }}
    </div>

    <!-- Heading -->
    <h1 class="text-green font-bold text-left text-2xl mb-8">{{ $heading }}</h1>

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



