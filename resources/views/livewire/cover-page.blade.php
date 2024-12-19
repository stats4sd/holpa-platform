<div class="explore-page">
    <img src="images/tpp_logo.png" alt="Logo" class="absolute h-24 w-auto hidden md:block"
         style="top: 15px; left: 10px; z-index: 999;"
    >

    <!-- Logo for small/medium screens - centered above title -->
    <img src="images/tpp_logo.png" alt="logo" class="absolute h-16 w-auto block md:hidden"
         style="top: 200px; left: 50%; transform: translateX(-50%); z-index: 999;"
    >


    <div class="relative">
        <!-- Background Image (absolutely positioned to go as a backdrop) -->
        <img src="images/landscape.jpg" alt="Background Image" class="w-full h-full md:max-h-[80vh] object-cover absolute" style="object-position: 50% 28%; z-index: 0">

        <!-- Overlay Content -->
        <div class="relative inset-0 flex flex-col items-center justify-center bg-black bg-opacity-50 pt-20 md:pt-2 z-10">
            <!-- Images -->
            <img src="images/wavy_lines.png" alt="Overlay Image" class="w-full z-100 absolute" style="top: 0px;">


            <!-- Headings -->
            <div class="relative flex items-center max-w-3xl md:pr-36 mb-0 md:mb-10 text-center md:text-left px-4 pt-20 md:pt-36">
                <div class="flex-grow">
                    <h1 class="text-white text-2xl sm:text-3xl md:text-4xl mb-2 md:mb-4">HOLPA - Holistic Localised Performance Assessment</h1>
                </div>
            </div>

            <!-- Explore Cards -->

            <div class="flex flex-col md:flex-row justify-center space-y-6 md:space-y-0 md:space-x-8 w-full items-center md:items-stretch px-4 mt-10 md:mt-16 pb-8">
                <!-- Tools Card -->
                <div class="explore-card bg-turquoisegreen flex flex-col items-center">
                    <img src="images/tools_line.png" alt="Icon 1" class="absolute top-[15px] right-[15px] h-8 w-8 md:h-10 md:w-10">
                    <div class="flex-1 text-center">
                        <h2 class="text-lg md:text-xl mb-4 md:mb-6">What is HOLPA?</h2>
                        <p class="mb-4 md:mb-6">Read about the purpose of the tool and find out more.</p>
                    </div>
                    <a href="{{ url('#what-is-holpa') }}" class="explore-card-button transparent-white-button hover-effect w-full md:w-auto flex justify-center items-center text-center">
                        FIND OUT MORE
                    </a>
                </div>

                <!-- Themes Card -->
                <div class="explore-card bg-brightblue flex flex-col items-center">
                    <img src="images/themes_line.png" alt="Icon 2" class="absolute top-[15px] right-[15px] h-8 w-8 md:h-10 md:w-10">
                    <div class="flex-1 text-center">
                        <h2 class="text-lg md:text-xl mb-4 md:mb-6">ONLINE TOOL</h2>
                        <p class="mb-4 md:mb-6">An online interface to help you tailor HOLPA to your context and conduct a survey.</p>
                    </div>
                    <a href="{{ url('#online-tool') }}" class="explore-card-button transparent-white-button hover-effect w-full md:w-auto flex justify-center items-center text-center">
                        READ MORE
                    </a>
                </div>

                <!-- Metrics Card -->
                <div class="explore-card bg-darkgreen flex flex-col items-center">
                    <img src="images/metrics_line.png" alt="Icon 3" class="absolute top-[15px] right-[15px] h-8 w-8 md:h-10 md:w-10">
                    <div class="flex-1 text-center">
                        <h2 class="text-lg md:text-xl mb-4 md:mb-6">RESULTS</h2>
                        <p class="mb-4 md:mb-6">Results from previous implementations.</p>
                    </div>
                    <a href="{{ url('#results') }}" class="explore-card-button transparent-white-button hover-effect w-full md:w-auto flex justify-center items-center text-center">
                        LEARN MORE
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="mx-auto mt-8 max-w-7xl px-4 sm:px-6 lg:px-8 divide-y-2 divide-green">

        <div id="what-is-holpa" class="py-6">
            <h2>WHAT IS HOLPA? </h2>
            <p>Ad veniam labore duis consequat eu elit reprehenderit et laborum dolore culpa nostrud tempor. Elit elit eu sit sit eiusmod laborum nisi veniam. Minim esse amet ullamco irure minim aliquip consequat exercitation pariatur aute eu in duis in anim. Dolor eu commodo do deserunt excepteur fugiat. Aliquip culpa reprehenderit dolore sit et nisi nisi in elit sunt. Commodo ex dolor exercitation quis cillum cillum aliquip esse magna cillum duis aute nostrud amet. Tempor anim mollit anim ad labore ut.</p>
            <p>Veniam esse reprehenderit cillum ipsum. Officia incididunt ipsum magna ullamco sunt duis exercitation occaecat enim qui cupidatat Lorem. Eiusmod minim excepteur nisi voluptate anim anim enim excepteur nulla. Non sint do velit.</p>
        </div>

        <div id="online-tool" class="py-6">
            <h2>ONLINE TOOL</h2>
            <p>Occaecat culpa aute est esse do deserunt qui do labore velit fugiat laborum culpa esse. Duis ea culpa id veniam excepteur aliqua laboris deserunt ipsum veniam nostrud elit eu voluptate. Id cillum aliqua nulla mollit reprehenderit eu. Lorem anim et reprehenderit ex anim aute magna est sint ipsum aute esse. Pariatur culpa voluptate eu. Aliqua proident consectetur officia consequat laborum est aliquip ex cupidatat ut enim incididunt sint mollit.</p>
        </div>

        <div id="results" class="py-6">
            <h2>RESULTS</h2>
            <p>Enim aute elit cillum laborum reprehenderit aute excepteur quis ut veniam ex. Do quis reprehenderit elit commodo esse mollit cillum incididunt nostrud aliqua sunt anim dolore. Cupidatat consectetur ea sunt consequat commodo aliquip nostrud ullamco fugiat ex tempor fugiat. Ullamco quis aliqua elit ipsum sint reprehenderit minim nisi labore sunt eiusmod consequat qui esse.</p>
            <p>Adipisicing officia non tempor commodo nulla aute ipsum irure Lorem eu nostrud adipisicing. Est mollit ex exercitation esse voluptate laboris fugiat. Voluptate aute quis reprehenderit nostrud eiusmod. Commodo est laborum aliqua quis velit tempor. Anim occaecat sint eiusmod. Sunt consequat mollit anim mollit occaecat laboris ea minim dolore excepteur aliqua ex. Culpa dolor deserunt dolore nisi elit. Id sunt eu laborum quis ipsum cupidatat et fugiat.</p>
        </div>
    </div>
</div>
