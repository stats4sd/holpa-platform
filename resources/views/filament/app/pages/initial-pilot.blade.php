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

                <div class="text-center p-4 flex justify-around align-items-center space-x-0 md:space-x-8 md:space-y-0 space-y-4 md:flex-row flex-col">
                    @foreach($xlsforms as $xlsform)

                        @if($xlsform->needs_update)

                            <div class="border border-gray-600 rounded-lg p-4 flex flex-col items-center justify-center space-y-4 space-x-4 h-100 px-20">
                                <div class="mx-auto"></div>"
                                <div class="mx-auto">This XLSForm is currently being updated. The QR code will appear when the new draft is ready</div>
                                <div class="mx-auto flex items-center justify-center">
                                    <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin fill-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 101" fill="none">
                                        <path d="M100 50.59c0-27.61-22.39-50-50-50S0 22.98 0 50.59c0 27.61 22.39 49.41 50 49.41s50-21.8 50-49.41zm-9 0c0 22.68-18.32 41-41 41-22.68 0-41-18.32-41-41 0-22.68 18.32-41 41-41 22.68 0 41 18.32 41 41z" fill="currentColor"/>
                                        <path d="M93.97 39.04c2.54-.64 4.06-3.23 3.02-5.73-1.23-2.95-3.67-8.53-9.25-13.37-5.59-4.84-11.54-6.52-14.49-7.15-2.64-.57-5.27 1.26-5.65 3.93-.6 4.05-.92 10.08-.92 16.68 0 6.62.32 12.65.93 16.7.37 2.62 2.92 4.46 5.61 3.95 2.98-.6 7.06-2.01 12.3-5.81 5.22-3.79 8.48-8.36 9.2-11.03.58-2.12-.19-4.4-2.13-5.18-.86-.35-1.78-.37-2.67-.08z" fill="currentFill"/>
                                    </svg>
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <h3>{{ $xlsform->title }}</h3>
                            </div>

                        @else

                            <div class="border border-gray-600 rounded-lg p-4 flex flex-col items-center justify-center space-y-4 space-x-4 h-100">
                                <div class="mx-auto">{{ QrCode::size(150)->generate($xlsform->draft_qr_code_string) }}</div>
                                <h3>{{ $xlsform->title }}</h3>
                                <h5 class="mx-auto">SCAN QR Code in ODK Collect</h5>
                            </div>
                        @endif
                    @endforeach
                </div>


            </div>

            <div class="pb-8">Any submissions to these DRAFT versions will appear below, so you can confirm that the data you have submitted has been recognised. Note that these DRAFTs are intended as a technical test of the forms themselves, and the DRAFT submissions are not intended to be kept and processed. To conduct a full pilot, including data export and a test of the analysis process, please go to the
                <a class="underline text-blue" href="{{ \App\Filament\App\Pages\Pilot::getUrl() }}">Localisation: Pilot</a> page.
            </div>

            {{ $this->table }}

        </div>
    </div>
</x-filament-panels::page>
