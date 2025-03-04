<!-- Header Section -->

<div class="bg-white shadow-md pt-8 ">
    <div class="container mx-auto xl:px-32">

        <!-- Heading -->
        <h1 class="text-green  text-left  mb-12">HOLPA Survey Dashboard</h1>

        @if (auth()->user()->latestTeam->xlsforms()->count() == 0)
        <!-- Error message -->
        <h3 class="text-white text-center mb-12 bg-blue">THIS TEAM DOES NOT HAVE ANY ODK FORMS. THIS IS DUE TO AN ISSUE WITH THE TEAM CREATION. PLEASE CONTACT {{ config('mail.to.support') }} FOR SUPPORT BEFORE CONTINUING.</h3>
        @endif

        <!-- Content -->
        <div class="grid grid-cols-6 md:grid-cols-2 gap-6 mb-14">

            <!-- Video -->
            <div class="pr-4 content-center rounded-md">
                <iframe class="w-96 h-52 rounded-md" src="https://www.youtube.com/embed/TODO_ADD_VIDEO_ID" frameborder="0" allowfullscreen></iframe>
            </div>

            <!-- Instructions -->
            <div class="rounded-md">
                <h2 class="text-green  mb-4">Instructions</h2>
                <p class="text-black mb-2">This is the HOLPA survey builder dashboard, designed to support and facilitate the customisation and delivery of the HOLPA survey, and allow teams to collect and manage HOLPA survey data.
                    <a href="/#what-is-holpa" class="font-semibold text-blue hover:text-dark-blue">Find out more about HOLPA here. </a>
                <p class="text-black mb-8">
                    The dashboard below sets out the tasks required to prepare and deliver the survey, tracks your progress, and will support you with customising and implementing the survey.
                    The sections do not have to be completed in order (although making certain changes, such as adding another language, may require reviewing some sections). Changes can be made at any point, even if a section has already been marked as complete.
                    You can mark each section as complete to help you keep track. </p>


                <!-- <div class="text-center">
                            <a href="" class="buttonb">
                                FIND OUT MORE
                            </a>
                        </div> -->
            </div>
        </div>
    </div>
</div>