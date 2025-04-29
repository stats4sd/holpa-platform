<x-filament-widgets::widget>
<x-instructions-sidebar>
        <x-slot:heading>Instructions</x-slot:heading>
        <x-slot:instructions>

            {{-- <div class="pr-4 content-center  mx-auto my-4">
                <iframe class="rounded-3xl" src="https://www.youtube.com/embed/TODO_ADD_VIDEO_ID" style="width: 560px; height: 315px;" frameborder="0" allowfullscreen></iframe>
            </div> --}}
            <div class="mx-12 mb-4">

        <p class="mb-2">
                    The next action is to add the details of all the farms that you will visit in your survey. Similar to adding locations, you can add farms manually or import a list from a spreadsheet file. The spreadsheet should be set up with the same structure as when adding locations at other levels. 
                    </p>
                    <p class="mb-2">
                    When importing a list, you will need to include columns with the farm unique code, and the unique code for the location it is in. When you upload the list, you need to select the location level the farms are located in. You can also optionally include extra columns with details that identify the farm, such as farm name, telephone number, family name etc, or for details that contain properties of the farm which may be useful for future analysis, such as the size of the farm. 
</p>


               
            </div>
        </x-slot:instructions>
    </x-instructions-sidebar>
    @if(!$this->hasLocations())
        <x-filament::section title="Locations" icon="heroicon-o-exclamation-circle" icon-color="danger" heading="No Viable Locations Found">
            <p>
                There are no locations that can have farms assigned. Please check the <a class="underline text-blue-600" href="{{\App\Filament\App\Clusters\LocationLevels\Resources\LocationLevelResource::getUrl('index')}}">sample frame page</a> to add administrative levels and locations before you import your list of farms.

            </p>
        </x-filament::section>
    @endif
</x-filament-widgets::widget>
