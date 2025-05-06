<div class="explore-page">


    <!-- Logo for small/medium screens - centered above title -->


    <div class="relative h-screen lg:h-auto">
        <!-- Background Image (absolutely positioned to go as a backdrop) -->
        <img src="images/landscape.jpg" alt="Background Image" class="w-full h-full md:max-h-[80vh] lg:max-h-[70vh] object-cover absolute" style="object-position:center; z-index: 0">

        <!-- Overlay Content -->
        <div class="relative inset-0 flex  text-center items-center justify-center h-full md:max-h-[80vh] lg:max-h-[70vh] bg-black bg-opacity-50 pt-20 lg:pt-2 z-10">
            <div class="w-max flex flex-col items-left relative lg:top-16">
                <!-- Headings -->
                <div class="relative flex items-center mb-0  text-center lg:text-left px-4 pt-20 lg:pt-36">
                    <div class="flex flex-col lg:flex-row  space-y-6 lg:space-y-0 lg:space-x-8 w-full items-center lg:items-stretch  mt-10 lg:mt-16 pb-8">
                        <div class="flex-grow max-w-3xl">
                            <h2 class="text-white text-5xl mb-2 lg:mb-4 font-extralight" style="letter-spacing: 0.3em;">HOLPA</h2>
                            <h1 class="text-hyellow text-2xl sm:text-3xl lg:text-5xl mb-2 lg:mb-4">Holistic Localised Performance Assessment for Agroecology</h1>
                        </div>


                        <div class="content-end flex justify-end flex-grow ">
                            <a href="{{ url('app') }}" class="self-center button hover:bg-white b-white border-2 rounded-full w-36 px-4 py-2 text-white hover:text-black font-semibold w-auto  items-center text-center">
                                LOG IN
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Explore Cards -->

                <div class="flex flex-col lg:flex-row justify-center space-y-6 lg:space-y-0 lg:space-x-8 w-full items-center lg:items-stretch px-4 mt-10 lg:mt-8 pb-8">
                    <!-- Tools Card -->
                    <div class="explore-card bg-orange flex flex-col items-center">
                        <img src="images/themes_line.png" alt="Icon 1" class="absolute top-[15px] right-[15px] h-8 w-8 lg:h-8 lg:w-8">
                        <div class="flex-1 text-center">
                            <h3 class="text-white text-lg lg:text-xl mb-4 lg:my-4 ">What is HOLPA?</h3>
                            <p class="mb-4 lg:mb-6">Read about the purpose of the tool and find out more.</p>
                        </div>
                        <a href="{{ url('#what-is-holpa') }}" class="button bg-orange hover:bg-white b-white border-2 rounded-full  px-4 py-2 text-white hover:text-orange font-semibold w-auto flex justify-center items-center text-center">
                            FIND OUT MORE
                        </a>
                    </div>

                    <!-- Themes Card -->
                    <div class="explore-card bg-green flex flex-col items-center">
                        <img src="images/tools_line.png" alt="Icon 2" class="absolute top-[15px] right-[15px] h-8 w-8 lg:h-8 lg:w-8">
                        <div class="flex-1 text-center ">
                            <h3 class=" text-white text-lg lg:text-xl mb-4 lg:my-4">ONLINE TOOL</h3>
                            <p class="mb-4 lg:mb-6">An online interface to help you tailor HOLPA to your context and conduct a survey.</p>
                        </div>
                        <a href="{{ url('#online-tool') }}" class="button bg-green hover:bg-white b-white border-2 rounded-full  px-4 py-2 text-white hover:text-green font-semibold w-auto flex justify-center items-center text-center">
                            READ MORE
                        </a>
                    </div>

                    <!-- Metrics Card -->
                    <div class="explore-card bg-blue flex flex-col items-center">
                        <img src="images/metrics_line.png" alt="Icon 3" class="absolute top-[15px] right-[15px] h-8 w-8 lg:h-8 lg:w-8">
                        <div class="flex-1 text-center">
                            <h3 class="text-white text-lg lg:text-xl mb-4 lg:my-4">RESULTS</h3>
                            <p class="mb-4 lg:mb-6">Results from previous implementations.</p>
                        </div>
                        <a href="{{ url('#results') }}" class="button bg-blue hover:bg-white b-white border-2 rounded-full  px-4 py-2 text-white hover:text-blue font-semibold w-auto flex justify-center items-center text-center">
                            LEARN MORE
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="mx-auto mt-28 md:mt-0 lg:mt-44 max-w-7xl px-4 sm:px-6 lg:px-8">

        <div id="what-is-holpa" class="py-6 lg:grid flex flex-col lg:grid-cols-2 gap-5  mb-12">
            <div class="col-span-1 h-96 mx-12 mb-6 lg:mr-6 lg:ml-0 lg:mb-0 rounded-2xl" style="background-image:url('images/person1.jpg');  background-position:center;" alt="Picture of a farmer">

            </div>

            <div class="col-span-1  flex-col px-16 place-content-center">
                <h3 class="text-3xl mb-8 text-hyellow">What is HOLPA? </h3>
                <p>HOLPA is a survey-based tool for collecting evidence of the holistic impact of agroecology. It contains three modules: context, agroecology adherence, performance (global, local). It is applicable at plot, farm-household, or landscape levels (only piloted at farm-household level so far). The survey is developed for use with the Open Data Kit (ODK), and there is a standardised set of R scripts to enable quick calculation of global performance and agroecology indicators.</p>
                <p>One of the key features of HOLPA is the inclusion of localised indicators. In addition to the global survey, HOLPA includes a comprehensive 7-step localisation process, to enable teams to tailor the survey to best meet the needs of local stakeholders, including the farmers themselves. This localisation process is described in detail in the documentation that accompanies the survey tool.</p>
            </div>
        </div>

        <div id="online-tool" class="py-6 lg:grid flex flex-col-reverse lg:grid-cols-2 gap-5  mb-12">


            <div class="col-span-1  flex-col px-16 place-content-center">
                <h3 class="text-3xl mb-8 text-hyellow">Online Tool</h3>
                <p>Teams wishing to implement HOLPA can download the survey tool in ODK, alongside the guidance for localisations. Alternatively, teams can request access to the beta version of the "HOLPA Online Tool" - a web-based platform that guides teams through the process of setting up, localising and implementing the survey. For more information about this tool, or to register your interest in trying the beta version, please use the links below. If you already have an account, you can log in below.</p>
                <div class="mt-8 place-content-center flex flex-row w-full">
                    <a href="{{ url('app') }} " class="buttona mx-2 px-4">Log in</a>
                    <a href="" class="buttonb px-4 mx-2">Register interest</a>
                </div>
            </div>
            <div class="col-span-1 h-96 mx-12 mb-6 lg:mr-6 lg:ml-0 lg:mb-0 rounded-2xl" style="background-image:url('images/crop1.jpg');  background-position:center; background-size: cover;" alt="Picture of crops">

            </div>
        </div>


        <div id="results" class="py-6 lg:grid flex flex-col lg:grid-cols-2 gap-5  mb-12">
            <div class="col-span-1 rounded-2xl h-96 mx-12 mb-6 lg:mr-6 lg:ml-0 lg:mb-0" style="background-image:url('images/person2.jpg');  background-position:center; background-size: cover;" alt="Picture of a farmer">


            </div>

            <div class="col-span-1  flex-col px-16 place-content-center">
                <h3 class="text-3xl mb-8 text-hyellow">Results </h3>
                <p>HOLPA has so far been implemented across 7 different countries, and the analysis by the core HOLPA team is ongoing. This section will be updated with more information, highlights and downloadable reports when they become available. </p>
            </div>
        </div>


    </div>
</div>
