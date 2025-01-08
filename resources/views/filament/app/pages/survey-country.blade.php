<x-filament-panels::page>

    <livewire:page-header-with-instructions
        instructions1='In this section, you will select the language or languages in which you plan to run the survey and either select an existing translation of the tool or create your own using a provided template.'
        instructions2=''
        instructions3='In the select translation table, choose the target language or languages for your survey. You can download and preview the translation if desired. You can add or remove translations at any point. '
        instructions4='If a suitable translation is not available for your target language, you will need to create one. Add the desired language, and it will appear in the table. Click update to download the translation template, which you will need to complete with translations for all the required fields. Once the translation is ready, return to this page to upload it, and the translation will be available for use. '

        instructionsmarkcomplete=' you have selected and (where needed) uploaded the completed translation for each language you intend to use for the survey. '
    />

    <div class="container   mx-auto xl:px-12 ">
        {{ $this->form }}
    </div>
</x-filament-panels::page>
