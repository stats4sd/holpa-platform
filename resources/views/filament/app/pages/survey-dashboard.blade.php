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
                <div class="flex drop-shadow-lg overflow-hidden col-span-12 xl:col-span-7 h-72">
                    <!-- Green Section -->
                    <div class=" bg-green text-white text-lg font-bold flex flex-col w-48 xxl:w-48 2xl:w-64 items-center justify-center p-4">
                        <img src="/images/context_icon.png" alt="Context Icon" class="w-12 mb-2">
                        <span class="mt-2 text-center">Context</span>
                        <!-- Progress bar -->
                         <!-- Examples of different progress amounts shown on different sections - needs someone who knows more than me to implement!  -->
                        <div class="w-3/4 bg-white bg-opacity-50 rounded-full h-2.5 mt-8">
                             <div class="bg-white h-2.5 rounded-full w-full" ></div>
                        </div>
                    </div>
                    <!-- White Section -->
                    <div class=" bg-white flex flex-grow py-6 ">
                        <div class="border-r border-gray-300 w-1/2 h-full">
                        <div class=" whitecard  px-4 2xl:px-8">
                            <h3 class="text-black font-bold mb-2">Add or manage additional data</h3>
                            <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
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
                                <div class="mb-6">
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
                        <div class="w-1/2">
                        <div class=" whitecard  px-4 2xl:px-8">
                            <h3 class="text-black font-bold mb-2">Survey languages</h3>
                            <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                            <div class="dashbuttondiv">
                                 <div class="mb-6">
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
                </div>

                <!-- Sampling card -->
                <div class="flex drop-shadow-lg overflow-hidden col-span-9 xl:col-span-5 h-72">
                    <!-- Green Section -->
                    <div class=" bg-green text-white text-lg font-bold flex flex-col w-48 2xl:w-64 items-center justify-center p-4">
                        <img src="/images/sampling_icon.png" alt="Sampling Icon" class="w-12 mb-2">
                        <span class="mt-2 text-center">Sampling</span>
                        <!-- Progress bar -->
                        <div class="w-3/4 bg-white bg-opacity-50 rounded-full h-2.5 mt-8">
                             <div class="bg-white h-2.5 rounded-full w-1/12" ></div>
                        </div>
                    </div>
                    <!-- White Section -->
                    <div class=" bg-white flex flex-grow py-6 ">
                        <div class=" whitecard px-6">
                            <h3 class="text-black font-bold mb-2">Sampling frame</h3>
                            <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                            <div class="dashbuttondiv">
                                <div class="mb-6">
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
                <div class="flex drop-shadow-lg overflow-hidden col-span-12 2xl:col-span-10 h-72">
                    <!-- Green Section -->
                    <div class=" bg-green text-white text-xl font-extrabold flex flex-col w-48 2xl:w-64  items-center justify-center p-4 relative">
                        <img src="/images/localisation_icon.png" alt="Localisation Icon" class="w-8 mb-2">
                        <span class="mt-2 text-center">Localisation</span>
                       <!-- Progress bar -->
                       <div class="w-3/4 bg-white bg-opacity-50 rounded-full h-2.5 mt-8">
                             <div class="bg-white h-2.5 rounded-full w-1/2" ></div>
                        </div>
                    </div>
                    <!-- White Section -->
                    <div class="bg-white py-6  flex flex-grow">
                    <div class="border-r border-gray-300 w-1/3 h-full">
                        <div class=" whitecard px-4 xl:px-8 flex-grow">
                            <h3 class="mb-2">Localisation: Place-based adaptations</h3>
                            <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                            <div class="text-center dashbuttondiv">

                                <div class="mb-6">
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
                        <div class="border-r border-gray-300 w-1/3 h-full">
                        <div class=" whitecard px-4  flex-grow  xl:px-8">
                            <h3 class="text-black font-bold mb-2">Localisation: LISP</h3>
                            <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                            <div class="text-center dashbuttondiv">
                                <div class="mb-6">
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
                        <div class="w-1/3 h-full">
                        <div class=" whitecard flex-grow px-4 xl:px-8">
                             <h3 class="text-black font-bold mb-2">Localisation: Pilot</h3>
                            <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                            <div class="dashbuttondiv">
                                 <div class="mb-6">
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
                    </div>
                </div>

                <!-- Data collection card -->
                <div class="flex drop-shadow-lg overflow-hidden col-span-9 md:col-span-6 xl:col-span-5 h-72">
                    <!-- Green Section -->
                    <div class=" bg-green text-white text-lg font-bold flex flex-col w-48 2xl:w-64  items-center justify-center p-4">
                        <img src="/images/data_collection_icon.png" alt="Data Collection Icon" class="w-9 mb-2">
                        <span class="mt-2 text-center">Data Collection</span>
                        <!-- Progress bar -->
                        <div class="w-3/4 bg-white bg-opacity-50 rounded-full h-2.5 mt-8">
                             <div class="bg-white h-2.5 rounded-full w-1/12" ></div>
                        </div>                        
                    </div>
                    <!-- White Section -->
                    <div class=" bg-white flex flex-grow py-6 ">
                    <div class=" whitecard px-6 xl:px-8 ">
                         <h3 class="text-black font-bold mb-2">Monitor data collection</h3>
                        <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                        <div class="dashbuttondiv">
                            <div class="mb-6">
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
                <div class="flex drop-shadow-lg overflow-hidden col-span-9 md:col-span-6 xl:col-span-5 h-72">
                    <!-- Green Section -->
                    <div class=" bg-green text-white text-lg font-bold flex flex-col w-48 2xl:w-64 items-center justify-center  p-4">
                            <img src="/images/data_analysis_icon.png" alt="Data Collection Icon" class="w-10 mb-2">
                        <span class="mt-2 text-center">Data Analysis</span>
                        <!-- Progress bar -->
                        <div class="w-3/4 bg-white bg-opacity-50 rounded-full h-2.5 mt-8">
                             <div class="bg-white h-2.5 rounded-full w-1/12" ></div>
                        </div>                        
                    </div>
                    <!-- White Section -->
                    <div class=" bg-white flex flex-grow py-6 ">
                    <div class="whitecard px-6 xl:px-8">
                         <h3 class="text-black font-bold mb-2">Download data</h3>
                        <p class="text-gray-600 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vehicula efficitur metus, id fermentum urna volutpat in.</p>
                        <div class="dashbuttondiv">
                            <div class="mb-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                    </svg>
                                    <span class="ml-1 inline text-xs font-semibold">NOT STARTED</span>
                                </div>       
                            <a href="{{ url(DataAnalysis::getUrl()) }}" class="buttona">
                                VIEW AND UPDATE
                            </a>
                        </div>
                    </div>
</div>
                </div>

            </div>
        </div>
    </div>

</x-filament-panels::page>
