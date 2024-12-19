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
    <div id="surveydash">

        <!-- Main Section -->
        <div class="container mx-auto xl:px-24">
            <div class="grid xl:grid-cols-12 gap-6 xl:mx-3">

                <!-- Context card -->
                <div class="flex flex-col lg:flex-row drop-shadow-lg overflow-hidden lg:h-72 col-span-12 lg:col-span-7">
                    <div class=" greensection">
                        <img src="/images/context_icon.png" alt="Context Icon" class="w-8 mb-2 ml-8 lg:ml-0">
                        <div class="w-3/4 mx-10 lg:w-full lg:mx-0 lg:text-center">
                            <span class="mt-2 text-center">Context</span>
                            <!-- Progress bar -->
                            <!-- Examples of different progress amounts shown on different sections - needs someone who knows more than me to implement!  -->
                            <div class="w-3/4 bg-white bg-opacity-50 rounded-full h-2.5 mt-8 lg:mx-auto">
                                <div class="bg-white h-2.5 rounded-full w-full" ></div>
                            </div>
                        </div>
                    </div>
                    <!-- White Section -->
                    <div class="whitesection">
                        <div class="whiteborderbox">
                            <div class=" whitecard ">
                                <div class="dashdescdiv">
                                    <h3 class="mb-2">Add or manage additional data</h3>
                                    <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                                </div>
                                <div class="dashbuttondiv">
                                    <!-- Options for "not started", "in progress" or "compeleted" described below - needs someone who knows more than me to implement each one.

                                    ****** NOT STARTED = nothing has been done yet, nothing edited or uploaded or saved.******
                                        <div class="mb-6">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                            </svg>
                                            <span class="ml-1 inline text-xs font-semibold">NOT STARTED</span>
                                        </div>

                                    ****** IN PROGRESS = some change has been saved - if possible some way of having it show progress if anything has been updated, added, saved etc. Sorry if this is a pain - we could discuss alternatives!******
                                        <div class="mb-6">
                                            <svg xmlns="http://www.w3.org/2000/svg"  class="h-5 inline " fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                            </svg>
                                            <span class="ml-1 inline text-xs font-semibold">IN PROGRESS</span>
                                        </div>


                                    ****** COMPLETE = user has clicked the button to mark it as complete. ******
                                        <div class="mb-6">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 inline " fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            <span class="ml-1 inline text-xs font-semibold">COMPLETE</span>
                                        </div>

                                    -->
                                    <div class="mb-6 w-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 inline " fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        <span class="ml-1 inline text-xs font-semibold">COMPLETE</span>
                                    </div>
                                    <a href="{{ url(AddData::getUrl()) }}" class="buttona">
                                        VIEW AND UPDATE
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="whitesection">
                        <div class=" whitecard ">
                            <div class="dashdescdiv">
                                <h3 class="mb-2">Survey languages</h3>
                                <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                            </div>
                            <div class="dashbuttondiv">
                                <div class="mb-6 w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 inline " fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <span class="ml-1 inline text-xs font-semibold">COMPLETE</span>
                                </div>
                                <a href="{{ url(SurveyLanguages::getUrl()) }}" class="buttona">
                                    VIEW AND UPDATE
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sampling card -->
                <div class="flex flex-col lg:flex-row drop-shadow-lg overflow-hidden col-span-12 lg:col-span-4 xl:col-span-5 lg:h-72">
                    <!-- Green Section -->
                    <div class=" greensection">
                        <img src="/images/sampling_icon.png" alt="Sampling Icon" class="w-8 mb-2 ml-8 lg:ml-0">
                        <div class="w-3/4 mx-10 lg:w-full lg:mx-0 lg:text-center">
                            <span class="mt-2 text-center">Sampling</span>
                            <!-- Progress bar -->
                            <div class="w-3/4 bg-white bg-opacity-50 rounded-full h-2.5 mt-8 lg:mx-auto">
                                <div class="bg-white h-2.5 rounded-full w-1/12" ></div>
                            </div>
                        </div>
                    </div>
                    <!-- White Section -->
                    <div class="whitesection">
                        <div class=" whitecard ">
                            <div class="dashdescdiv">
                                <h3 class="mb-2">Sampling frame</h3>
                                <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                            </div>
                            <div class="dashbuttondiv">
                                <div class="mb-6 w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                    </svg>
                                    <span class="ml-1 inline text-xs font-semibold">NOT STARTED</span>
                                </div>
                                <a href="{{ url(Sampling::getUrl()) }}" class="buttona">
                                    VIEW AND UPDATE
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Localisation card -->
                <div class="flex flex-col lg:flex-row drop-shadow-lg overflow-hidden col-span-12 lg:col-span-11 xl:col-span-12 ">
                    <!-- Green Section -->
                    <div class=" greensection ">
                        <img src="/images/localisation_icon.png" alt="Localisation Icon" class="w-8 mb-2 ml-8 lg:ml-0">
                        <div class="w-3/4 mx-10 lg:w-full lg:mx-0 lg:text-center">
                            <span class="mt-2 text-center">Localisation</span>
                            <!-- Progress bar -->
                            <div class="w-3/4 bg-white bg-opacity-50 rounded-full h-2.5 mt-8 lg:mx-auto">
                                <div class="bg-white h-2.5 rounded-full w-1/12" ></div>
                            </div>
                        </div>
                    </div>
                    <!-- White Section -->
                    <div class="whitesection">
                        <div class="whiteborderbox">
                            <div class=" whitecard ">
                                <div class="dashdescdiv">
                                    <h3 class="mb-2">Place-based adaptations</h3>
                                    <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                                </div>
                                <div class="dashbuttondiv">
                                    <div class="mb-6 w-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 inline " fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        <span class="ml-1 inline text-xs font-semibold">COMPLETE</span>
                                    </div>
                                    <a href="{{ url(PlaceAdaptations::getUrl()) }}" class="buttona">
                                        VIEW AND UPDATE
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="whitesection">
                        <div class="whiteborderbox">
                            <div class=" whitecard ">
                                <div class="dashdescdiv">
                                    <h3 class="mb-2">Localisation: LISP</h3>
                                    <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                                </div>
                                <div class="dashbuttondiv">
                                    <div class="mb-6 w-full">
                                        <svg xmlns="http://www.w3.org/2000/svg"  class="h-5 inline " fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                        </svg>
                                        <span class="ml-1 inline text-xs font-semibold">IN PROGRESS</span>
                                    </div>
                                    <a href="{{ url(Lisp::getUrl()) }}" class="buttona">
                                        VIEW AND UPDATE
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="whitesection">
                        <div class=" whitecard ">
                            <div class="dashdescdiv">
                                <h3 class="mb-2">Localisation: Pilot</h3>
                                <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                            </div>
                            <div class="dashbuttondiv">
                                <div class="mb-6 w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                    </svg>
                                    <span class="ml-1 inline text-xs font-semibold">NOT STARTED</span>
                                </div>
                                <a href="{{ url(Pilot::getUrl()) }}" class="buttona">
                                    VIEW AND UPDATE
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class=" bg-white hidden xl:w-16 2xl:w-1/12 xl:flex">

                    </div>
                </div>

                <!-- Data collection card -->
                <div class="flex flex-col lg:flex-row drop-shadow-lg overflow-hidden col-span-12 lg:col-span-5 lg:h-72">
                    <!-- Green Section -->
                    <div class=" greensection">
                        <img src="/images/data_collection_icon.png" alt="Data Collection Icon" class="w-8 mb-2 ml-8 lg:ml-0">
                        <div class="w-3/4 mx-10 lg:w-full lg:mx-0 lg:text-center">
                            <span class="mt-2 text-center">Data Collection</span>
                            <!-- Progress bar -->
                            <div class="w-3/4 bg-white bg-opacity-50 rounded-full h-2.5 mt-8 lg:mx-auto">
                                <div class="bg-white h-2.5 rounded-full w-1/12" ></div>
                            </div>
                        </div>
                    </div>
                    <!-- White Section -->
                    <div class="whitesection">
                        <div class=" whitecard ">
                            <div class="dashdescdiv">
                                <h3 class="mb-2">Monitor data collection</h3>
                                <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                            </div>
                            <div class="dashbuttondiv">
                                <div class="mb-6 w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                    </svg>
                                    <span class="ml-1 inline text-xs font-semibold">NOT STARTED</span>
                                </div>
                                <a href="{{ url(DataCollection::getUrl()) }}" class="buttona">
                                    VIEW AND UPDATE
                                </a>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Data analysis card -->
                <div class="flex flex-col lg:flex-row drop-shadow-lg overflow-hidden col-span-12 lg:col-span-5 lg:h-72">
                    <!-- Green Section -->
                    <div class=" greensection">
                        <img src="/images/data_analysis_icon.png" alt="Data Aanalysis Icon" class="w-8 mb-2 ml-8 lg:ml-0">
                        <div class="w-3/4 mx-10 lg:w-full lg:mx-0 lg:text-center">
                            <span class="mt-2 text-center">Data Analysis</span>
                            <!-- Progress bar -->
                            <div class="w-3/4 bg-white bg-opacity-50 rounded-full h-2.5 mt-8 lg:mx-auto">
                                <div class="bg-white h-2.5 rounded-full w-1/12" ></div>
                            </div>
                        </div>
                    </div>
                    <!-- White Section -->
                    <div class="whitesection">
                        <div class=" whitecard ">
                            <div class="dashdescdiv">
                                <h3 class="mb-2">Download data</h3>
                                <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                            </div>
                            <div class="dashbuttondiv">
                                <div class="mb-6 w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                    </svg>
                                    <span class="ml-1 inline text-xs font-semibold">NOT STARTED</span>
                                </div>
                                <a href="{{ DataAnalysis::getUrl() }}" class="buttona">
                                    VIEW AND DOWNLOAD DATA
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-filament-panels::page>
