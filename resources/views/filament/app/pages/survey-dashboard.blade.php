<?php
use App\Filament\App\Pages\MoreInstructions;
use App\Filament\App\Pages\AddData;
use App\Filament\App\Pages\SurveyLanguages;
use App\Filament\App\Pages\Sampling;
use App\Filament\App\Pages\PlaceAdaptations;
use App\Filament\App\Pages\LISP;
use App\Filament\App\Pages\Pilot;
use App\Filament\App\Pages\DataCollection;
use App\Filament\App\Pages\DataAnalysis;
?>

<x-filament-panels::page>
    <div>

        <!-- Header Section -->
        <div class="bg-white shadow-md pt-8 px-4">

            <!-- Heading -->
            <h1 class="text-green-600 font-bold text-left text-2xl mb-8">HOLPA Survey Dashboard</h1>

            <!-- Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">

                <!-- Video -->
                <div class="p-4 rounded-md">
                    <iframe class="w-full h-64 rounded-md" src="https://www.youtube.com/embed/TODO_ADD_VIDEO_ID" frameborder="0" allowfullscreen></iframe>
                </div>

                <!-- Instructions -->
                <div class="p-4 rounded-md">
                    <h2 class="text-green-600 font-bold text-lg mb-4">Instructions</h2>
                    <p class="text-black mb-16">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ac venenatis elit. Vivamus non urna ac turpis hendrerit tincidunt ut eget risus. Curabitur sagittis, ex a consectetur convallis, libero nisi efficitur sapien, non eleifend enim lectus vel leo. Morbi tincidunt libero ut nunc scelerisque, eget fringilla nulla volutpat. Aliquam feugiat massa sit amet arcu convallis, et iaculis ligula facilisis. Etiam accumsan magna et ipsum facilisis, at malesuada nulla ornare.</p>

                    <div class="text-center">
                        <a href="{{ url(MoreInstructions::getUrl()) }}" class="bg-red-600 text-white py-2 px-6 rounded-full hover:bg-red-700 transition duration-200">
                            FIND OUT MORE
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <!-- Context card -->
            <div class="flex shadow-md overflow-hidden">
                <div class="w-1/3 bg-green-600 text-white text-lg font-bold flex items-center justify-center p-4">Context</div>
                <div class="w-2/3 bg-white p-4">
                    <h3 class="text-black font-bold mb-2">Add or manage additional data</h3>
                    <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                    <div class="text-right mb-4">
                        <a href="{{ url(AddData::getUrl()) }}" class="bg-blue-600 text-white py-2 px-4 rounded-full hover:bg-blue-700 transition duration-200">
                            VIEW AND UPDATE
                        </a>
                    </div>
                    <hr class="my-4 border-gray-300">
                    <h3 class="text-black font-bold mb-2">Survey languages</h3>
                    <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                    <div class="text-right">
                        <a href="{{ url(SurveyLanguages::getUrl()) }}" class="bg-blue-600 text-white py-2 px-4 rounded-full hover:bg-blue-700 transition duration-200">
                            VIEW AND UPDATE
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sampling card -->
            <div class="flex shadow-md overflow-hidden">
                <div class="w-1/3 bg-green-600 text-white text-lg font-bold flex items-center justify-center p-4">Sampling</div>
                <div class="w-2/3 bg-white p-4">
                    <h3 class="text-black font-bold mb-2">Sampling frame</h3>
                    <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                    <div class="text-right">
                        <a href="{{ url(Sampling::getUrl()) }}" class="bg-blue-600 text-white py-2 px-4 rounded-full hover:bg-blue-700 transition duration-200">
                            VIEW AND UPDATE
                        </a>
                    </div>
                </div>
            </div>

            <!-- Localisation card -->
            <div class="flex shadow-md overflow-hidden col-span-2 md:col-span-1">
                <div class="w-1/3 bg-green-600 text-white text-lg font-bold flex items-center justify-center p-4">Localisation</div>
                <div class="w-2/3 bg-white p-4">
                    <h3 class="text-black font-bold mb-2">Localisation: Place-based adaptations</h3>
                    <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                    <div class="text-right mb-4">
                        <a href="{{ url(PlaceAdaptations::getUrl()) }}" class="bg-blue-600 text-white py-2 px-4 rounded-full hover:bg-blue-700 transition duration-200">
                            VIEW AND UPDATE
                        </a>
                    </div>
                    <hr class="my-4 border-gray-300">
                    <h3 class="text-black font-bold mb-2">Localisation: LISP</h3>
                    <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                    <div class="text-right mb-4">
                        <a href="{{ url(LISP::getUrl()) }}" class="bg-blue-600 text-white py-2 px-4 rounded-full hover:bg-blue-700 transition duration-200">
                            VIEW AND UPDATE
                        </a>
                    </div>
                    <hr class="my-4 border-gray-300">
                    <h3 class="text-black font-bold mb-2">Localisation: Pilot</h3>
                    <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                    <div class="text-right">
                        <a href="{{ url(Pilot::getUrl()) }}" class="bg-blue-600 text-white py-2 px-4 rounded-full hover:bg-blue-700 transition duration-200">
                            VIEW AND UPDATE
                        </a>
                    </div>
                </div>
            </div>

            <!-- Data collection card -->
            <div class="flex shadow-md overflow-hidden">
                <div class="w-1/3 bg-green-600 text-white text-lg font-bold flex items-center justify-center p-4">Data Collection</div>
                <div class="w-2/3 bg-white p-4">
                    <h3 class="text-black font-bold mb-2">Monitor data collection</h3>
                    <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                    <div class="text-right">
                        <a href="{{ url(DataCollection::getUrl()) }}" class="bg-blue-600 text-white py-2 px-4 rounded-full hover:bg-blue-700 transition duration-200">
                            VIEW AND UPDATE
                        </a>
                    </div>
                </div>
            </div>

            <!-- Data analysis card -->
            <div class="flex shadow-md overflow-hidden">
                <div class="w-1/3 bg-green-600 text-white text-lg font-bold flex items-center justify-center p-4">Data Analysis</div>
                <div class="w-2/3 bg-white p-4">
                    <h3 class="text-black font-bold mb-2">Download data</h3>
                    <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                    <div class="text-right">
                        <a href="{{ url(DataAnalysis::getUrl()) }}" class="bg-blue-600 text-white py-2 px-4 rounded-full hover:bg-blue-700 transition duration-200">
                            VIEW AND UPDATE
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-filament-panels::page>
