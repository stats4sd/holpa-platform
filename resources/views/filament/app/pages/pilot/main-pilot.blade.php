<x-filament-panels::page class="px-10 h-full">


{{--        instructions1='At this stage, the ODK forms are ready for a full pilot test.'--}}
{{--        instructions2="The information below will help you set up the enumerator devices to access your locally-adapted ODK forms."--}}
{{--        instructions3="You may wish to share the video on this page with the enumerator team before the training if they are not familiar with ODK Collect."--}}
{{--        videoUrl='https://www.youtube.com/embed/VIDEO_ID'--}}


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

                        When you make changes to the forms on the platform, they are not automatically updated on your device. This is so that you can make changes, test them as
                        <a class="underline text-blue" href="{{ \App\Filament\App\Pages\PlaceAdaptations\InitialPilot::getUrl() }}">DRAFT VERSIONS</a> and confirm they are working as expected before updating the versions that your enumerator team will see.

                        <br/><br/>

                        If there are changes that can be published, you can do so by clicking the
                        <b>Publish</b> button on the table below. We highly recommend reviewing the forms as DRAFT versions before publishing. You can do so on the
                        <a class="underline text-blue" href="{{ \App\Filament\App\Pages\PlaceAdaptations\InitialPilot::getUrl() }}">Initial Pilot Page</a>.

                    </div>


                </div>
            </div>

            <div class="flex justify-center mb-8">
                <x-filament::tabs>
                    <x-filament::tabs.item wire:click="$set('tab', 'xlsforms')" :active="$tab === 'xlsforms'">
                        Survey Forms
                    </x-filament::tabs.item>
                    <x-filament::tabs.item wire:click="$set('tab', 'submissions')" :active="$tab === 'submissions'">
                        Submissions
                    </x-filament::tabs.item>
                </x-filament::tabs>
            </div>

            @if ($tab === 'xlsforms')
                <livewire:xlsforms-table-view/>
            @elseif ($tab === 'submissions')
                <livewire:submissions-table-view/>
            @endif
        </div>
    </div>
</x-filament-panels::page>
