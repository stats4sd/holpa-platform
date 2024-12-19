<div class="mx-auto">
    <div class="surveyblocks pb-24 mb-32 pt-12 px-12">
        <div class="grid grid-cols-3 gap-4 h-max mb-12">
            <a class="{{ request()->url() === \App\Filament\App\Pages\TimeFrame::getUrl() ? 'tabbuttons' : '' }}  rounded-2xl cursor-pointer lisptabs bg-gray-100 " href="{{\App\Filament\App\Pages\TimeFrame::getUrl() }}">
                <livewire:rounded-square
                    heading="Time Frame"
                    description="Define the time frame for many recall questions."
                />
            </a>

            <a class="{{ request()->url() === \App\Filament\App\Pages\DietDiversity::getUrl() ? 'tabbuttons' : '' }}  rounded-2xl cursor-pointer lisptabs bg-gray-100" href="{{ \App\Filament\App\Pages\DietDiversity::getUrl() }}">
                <livewire:rounded-square
                    heading="Diet Quality"
                    description="Select the Diet Quality module for your country / context."
                />
            </a>
            <a class="{{ request()->url() === App\Filament\App\Clusters\Localisations\Resources\ChoiceListEntryResource::getUrl('index') ? 'tabbuttons' : '' }}  rounded-2xl cursor-pointer lisptabs bg-gray-100" href="{{ \App\Filament\App\Clusters\Localisations\Resources\ChoiceListEntryResource::getUrl('index') }}">
                <livewire:rounded-square
                    heading="Choice List Entries"
                    description="Customise the entries for units, crops, and other select questions"
                />
            </a>
        </div>
    </div>
