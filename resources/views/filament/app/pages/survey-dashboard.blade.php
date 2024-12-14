<?php
use App\Filament\App\Pages\MoreInstructions;
use App\Filament\App\Pages\AddData;
use App\Filament\App\Pages\SurveyLanguages;
use App\Filament\App\Pages\Sampling;
use App\Filament\App\Pages\PlaceAdaptations;
use App\Filament\App\Pages\Lisp;
use App\Filament\App\Pages\Pilot;
use App\Filament\App\Pages\DataCollection;
use App\Filament\App\Pages\DataAnalysis;
?>

<x-filament-panels::page>
    <div>

        <!-- Main Section -->
        <div class="container mx-auto xl:px-32">
            <div class="grid lg:grid-cols-4 gap-8">

                <!-- Context card -->
                <div class="flex shadow-md overflow-hidden md:col-span-2 h-80">
                    <!-- Green Section -->
                    <div class="w-1/4 bg-green text-white text-lg font-bold flex flex-col items-center justify-center p-4">
                        <img src="/images/context_icon.png" alt="Context Icon" class="w-12 mb-2">
                        <span class="mt-2 text-center">Context</span>
                    </div>
                    <!-- White Section -->
                    <div class="w-3/4 bg-white flex p-4">
                        <div class="w-1/2 pr-4 border-r border-gray-300">
                            <h3 class="text-black font-bold mb-2">Add or manage additional data</h3>
                            <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                            <div class="text-center mb-4">
                                <a href="{{ url(AddData::getUrl()) }}" class="bg-blue text-white py-2 px-4 rounded-full hover-effect">
                                    VIEW AND UPDATE
                                </a>
                            </div>
                        </div>
                        <div class="w-1/2 pl-4">
                            <h3 class="text-black font-bold mb-2">Survey languages</h3>
                            <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                            <div class="text-center">
                                <a href="{{ url(SurveyLanguages::getUrl()) }}" class="bg-blue text-white py-2 px-4 rounded-full hover-effect">
                                    VIEW AND UPDATE
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sampling card -->
                <div class="flex shadow-md overflow-hidden md:col-span-2 h-80">
                    <div class="w-1/4 bg-green text-white text-lg font-bold flex flex-col items-center justify-center p-4">
                        <img src="/images/sampling_icon.png" alt="Sampling Icon" class="w-12 mb-2">
                        <span class="mt-2 text-center">Sampling</span>
                    </div>
                    <div class="w-3/4 bg-white p-4">
                        <h3 class="text-black font-bold mb-2">Sampling frame</h3>
                        <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                        <div class="text-center">
                            <a href="{{ url(Sampling::getUrl()) }}" class="bg-blue text-white py-2 px-4 rounded-full hover-effect">
                                VIEW AND UPDATE
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Localisation card -->
                <div class="flex shadow-md overflow-hidden md:col-span-4 h-80">
                    <!-- Green Section -->
                    <div class="w-2/8 bg-green text-white text-lg font-bold flex flex-col items-center justify-center p-4">
                        <img src="/images/localisation_icon.png" alt="Localisation Icon" class="w-8 mb-2">
                        <span class="mt-2 text-center">Localisation</span>
                    </div>
                    <!-- White Section -->
                    <div class="w-6/8 bg-white flex p-4">
                        <div class="w-1/3 px-6 border-r border-gray-300">
                            <h3 class="text-black font-bold mb-2">Localisation: Place-based adaptations</h3>
                            <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                            <div class="text-center mb-4">
                                <a href="{{ url(PlaceAdaptations::getUrl()) }}" class="bg-blue text-white py-2 px-4 rounded-full hover-effect">
                                    VIEW AND UPDATE
                                </a>
                            </div>
                        </div>
                        <div class="w-1/3 px-6 border-r border-gray-300">
                            <h3 class="text-black font-bold mb-2">Localisation: LISP</h3>
                            <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                            <div class="text-center mb-4">
                                <a href="{{ url(Lisp::getUrl()) }}" class="bg-blue text-white py-2 px-4 rounded-full hover-effect">
                                    VIEW AND UPDATE
                                </a>
                            </div>
                        </div>
                        <div class="w-1/3 px-6">
                            <h3 class="text-black font-bold mb-2">Localisation: Pilot</h3>
                            <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                            <div class="text-center">
                                <a href="{{ url(Pilot::getUrl()) }}" class="bg-blue text-white py-2 px-4 rounded-full hover-effect">
                                    VIEW AND UPDATE
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data collection card -->
                <div class="flex shadow-md overflow-hidden md:col-span-2 h-80">
                    <!-- Green Section -->
                    <div class="w-1/4 bg-green text-white text-lg font-bold flex flex-col items-center justify-center p-4">
                        <img src="/images/data_collection_icon.png" alt="Data Collection Icon" class="w-9 mb-2">
                        <span class="mt-2 text-center">Data Collection</span>
                    </div>
                    <!-- White Section -->
                    <div class="w-3/4 bg-white p-4">
                        <h3 class="text-black font-bold mb-2">Monitor data collection</h3>
                        <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                        <div class="text-center">
                            <a href="{{ url(DataCollection::getUrl()) }}" class="bg-blue text-white py-2 px-4 rounded-full hover-effect">
                                VIEW AND UPDATE
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Data analysis card -->
                <div class="flex shadow-md overflow-hidden md:col-span-2 h-80">
                    <!-- Green Section -->
                    <div class="w-1/4 bg-green text-white text-lg font-bold flex flex-col items-center justify-center p-4">
                            <img src="/images/data_analysis_icon.png" alt="Data Collection Icon" class="w-10 mb-2">
                        <span class="mt-2 text-center">Data Analysis</span>
                    </div>
                    <!-- White Section -->
                    <div class="w-3/4 bg-white p-4">
                        <h3 class="text-black font-bold mb-2">Download data</h3>
                        <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                        <div class="text-center">
                            <a href="{{ url(DataAnalysis::getUrl()) }}" class="bg-blue text-white py-2 px-4 rounded-full hover-effect">
                                VIEW AND UPDATE
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
