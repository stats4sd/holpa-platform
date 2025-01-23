<x-filament-panels::page>
    <div class="container mx-auto  ">
        <div class="surveyblocks pt-16 pb-24 mb-32 px-12 lg:px-16">
            <h3>Instructions</h3>
            <p class="mb-8">Please select the country and languages for your survey below.</p>

            {{ $this->form }}

            <a href="{{ \App\Filament\App\Pages\SurveyLanguages::getUrl() }}" class="buttona block max-w-sm mx-auto md:inline-block mb-6 md:mb-0 mt-12">Save and Return</a>
        </div>
</x-filament-panels::page>
