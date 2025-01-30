<x-filament-panels::page class="px-10 h-full">

    <livewire:page-header-with-instructions
        instructions1='At this stage, the ODK forms are ready for a full pilot test.'
        instructions2="The information below will help you set up the enumerator devices to access your locally-adapted ODK forms."
        instructions3="You may wish to share the video on this page with the enumerator team before the training if they are not familiar with ODK Collect."
        videoUrl='https://www.youtube.com/embed/VIDEO_ID'
    />

    <div class="container mx-auto xl:px-12">
        <div class="surveyblocks p-10">

            <div class="mb-10">
                <div class="flex flex-row">
                    <div class="mr-4 text-center basis-1/4 border border-gray-400 rounded-lg p-4 bg-white flex flex-col justify-center space-y-4">
                        <div class="mx-auto">{{ QrCode::size(150)->generate(\Stats4sd\FilamentOdkLink\Services\HelperService::getCurrentOwner()->odk_qr_code) }}</div>
                        <h5 class="">SCAN QR Code in ODK Collect</h5>
                    </div>

                    <div class="basis-3/4 px-12">
                        Your project team has been setup. To link your Android device, install and open
                        <b>ODK Collect</b>. When asked for project details, scan the QR code on this page. Your device will be linked and you will have access to the forms listed below.
                        <br/><br/>
                        Both forms will be available to you. The platform will automatically update the forms when you make local adaptations. If for some reason the survey is not fully up to date with your latest changes, you may manually deploy the latest version by clicking the
                        <b>Deploy</b> button in the table below.
                        <br/><br/>
                        {{ $this->viewSubmissionsAction }}
                    </div>


                </div>
            </div>

            {{ $this->table }}

        </div>
    </div>
</x-filament-panels::page>
