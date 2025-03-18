<x-filament-panels::page class="px-10 h-full">

    <livewire:page-header-with-instructions
        instructions1='In this step, you have the option to review the ODK Survey with the current adaptations. You may return to this page at any time to review the latest version of your surveys.'
        instructions2="It is recommended to review both survey forms with a local researcher or practitioner. If this is the first time using ODK, you can review the instructional video, which will show you how to access the surveys through the ODK Collect mobile app, or through the Web browser."
        instructions3="The forms you access through this page are considered <b>Test</b> forms. You can use them to test your adaptations, and any submissions you make will be saved as <b>test</b> submissions, and not included in the final dataset."
        videoUrl='https://www.youtube.com/embed/VIDEO_ID'
    />

    <div class="container mx-auto xl:px-12">
        <div class="surveyblocks p-10">

            <div class="mb-4">
                <h3>TESTING THE SURVEYS</h3>
                <div class="">
                    The QR codes below use ODK Central's "Draft" feature to allow you to access test versions of the surveys. These versions always have the latest adaptations, and allow you to test changes without affecting the published versions that are shared with your enumerators.
                </div>

                <div class="my-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">Please do not share these codes with enumerators! All submissions to these test forms are temporary and will be reset whenever changes to the form or local adaptations are made.</div>

                <div class="text-center p-4 flex justify-around align-items-center space-y-4 space-x-8">
                    @foreach($xlsforms as $xlsform)
                        <div class="border border-gray-600 rounded-lg p-4 flex flex-col items-center justify-center space-y-4 space-x-4">
                            <div class="mx-auto">{{ QrCode::size(150)->generate($xlsform->draft_qr_code_string) }}</div>
                            <h3>{{ $xlsform->title }}</h3>
                            <h5 class="mx-auto">SCAN QR Code in ODK Collect</h5>
                        </div>
                    @endforeach
                </div>


            </div>

            <div class="pb-8">Any submissions to these DRAFT versions will appear below, so you can confirm that the data you have submitted has been recognised. Note that these DRAFTs are intended as a technical test of the forms themselves, and the DRAFT submissions are not intended to be kept and processed. To conduct a full pilot, including data export and a test of the analysis process, please go to the <a class="underline text-blue" href="{{ \App\Filament\App\Pages\Pilot::getUrl() }}">Localisation: Pilot</a> page.</div>

            {{ $this->table }}

        </div>
    </div>
</x-filament-panels::page>
