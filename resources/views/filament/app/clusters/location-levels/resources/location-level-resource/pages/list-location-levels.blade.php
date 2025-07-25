<x-filament-panels::page
    @class([
        'fi-resource-list-records-page',
        'fi-resource-' . str_replace('/', '-', $this->getResource()::getSlug()),
    ])
>

<x-instructions-sidebar>
        <x-slot:heading>Instructions</x-slot:heading>
        <x-slot:instructions>

            {{-- <div class="pr-4 content-center  mx-auto my-4">
                <iframe class="rounded-3xl" src="https://www.youtube.com/embed/TODO_ADD_VIDEO_ID" style="width: 560px; height: 315px;" frameborder="0" allowfullscreen></iframe>
            </div> --}}
            <div class="mx-12 mb-4">
                
                <p class="mb-2"> 
                    On the location levels page, you first need to add the names of the different levels:
                </p>
                <ul class="instructions_list list-disc ">
                    <li>
                    Click on "new location level". 
                    </li>
                    <li>
                    Indicate whether the location level you are adding is a sub-level of another level - in our province > district > village example, district is a sub-level of province and village is a sub-level of district. 
                    </li>
                    <li>
                    Add the name for the level. 
                    </li>
                    <li>
                    Indicate whether there are farms at this level - This means that they are within this level but not in a lower sub-level; for example, there are farms at the village level but not the province level. 
                    </li>
                    <li>
                    Click to create the level, or to create and immediately add another level. 
                    </li>
                    </ul>
                    <p class="mb-2">
                    Once you have added levels, you can select them to view a list of the locations that have been added at that level and add locations. You have to option to import them from an excel file or add them manually. 
                    </p>
                    <p class="mb-2">

                    To import locations, upload an excel spreadsheet with the required details:
                    </p>
                     <ul class="instructions_list list-disc ">
                    <li>
                    A column with the name of the location
                    </li>
                    <li>
                    A column with the unique IDs for the locations. (These can be generated however you like, but each one must be unique)
                    </li>
                    <li>                    
                    If the location level you are adding to is a sub-level of another location level, then you need to also have a column with the name and unique ID of the location in the level above (in our province > district > village example, "Village A" might be located in "District C", and you would need to include the details of both.) 
                    </li>
                    <li>
                    The first row of the worksheet should contain column headings, not your first location. 
                    </li>
                    <li>
                    Make sure the data to be imported is in the first worksheet of the spreadsheet.
                    </li>
                    <li>
                    You should have a column for location name and a column for unique codes for the locations.
                    </li>
                    <li>
                    It does not matter what order the columns are in, what column headers you use, or if other data is in the spreadsheet.
                    </li></ul>
                    <p class="mb-2"> 
                    Once you have uploaded the list, use the column mapping options to select the column for names and the column for unique code. 
  
                    You have the option to override all previously added locations with the new locations from the uploaded spreadsheet. When you are ready, click submit. 
</p>
<p class="mb-2"> 
                    To add locations manually, click on "Add new [location name]", and type in the name and code, then click on "Create" or "Create and create another". Add locations for for all the location levels. 
                    </p>

               
            </div>
        </x-slot:instructions>
    </x-instructions-sidebar>

    <div class="container">

        <div class="flex flex-col gap-y-6">
            <x-filament-panels::resources.tabs/>

            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE, scopes: $this->getRenderHookScopes()) }}

            {{ $this->table }}

            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_AFTER, scopes: $this->getRenderHookScopes()) }}
        </div>

    </div>
</x-filament-panels::page>
