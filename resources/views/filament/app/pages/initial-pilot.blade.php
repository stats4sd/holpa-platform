<x-filament-panels::page class="px-10 h-full">

    <livewire:page-header-with-instructions
            instructions1='In this step, you have the option to review the ODK Survey with the current adaptations. You may return to this page at any time to review the latest version of your surveys.'
            instructions2="It is recommended to review both survey forms with a local researcher or practitioner. If this is the first time using ODK, you can review the instructional video, which will show you how to access the surveys through the ODK Collect mobile app, or through the Web browser."
            instructions3="The forms you access through this page are considered <b>Test</b> forms. You can use them to test your adaptations, and any submissions you make will be saved as <b>test</b> submissions, and not included in the final dataset."
            videoUrl='https://www.youtube.com/embed/VIDEO_ID'
    />

    <div class="container mx-auto xl:px-12">
        <div class="surveyblocks p-10">

            <div class="mb-10">
                <div class="flex flex-row">
                    <div class="mr-4 text-center basis-1/4 border border-gray-400 rounded-lg p-4 bg-white flex flex-col justify-center space-y-4">
                        <div class="mx-auto">{{ QrCode::size(150)->generate(\App\Services\HelperService::getSelectedTeam()->odk_qr_code) }}</div>
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
