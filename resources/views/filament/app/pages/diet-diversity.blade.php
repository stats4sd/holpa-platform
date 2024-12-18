<x-filament-panels::page>

    <p>HOLPA uses an internationally validated indicator for "dietary diversity". The questions in this section ask whether members of the household have consumed anything from specific food groups within the last 24 hours, such as grain food, tubers, pulses, green veg, etc. The default survey has all the needed questions, but does not include lists of locally contextualised example foods for each group.</p>
    <p>The << name of website with link>> site includes localised versions of these questions, with relevant example foods for each category for over 100 countries. This platform includes these localised modules, so you can select the option that fits your context below.</p>
    <p>It is assumed that you are conducting HOLPA within one country. If you are working across multiple countries, we recommend creating a different "team" for each country so that you can make different customisations to the survey within the different countries.</p>

    {{ $this->form }}


    <p>Below are the questions that form this module. When you select your country, this table will update to show the text that will appear in your version of the survey.</p>
    {{ $this->table }}
</x-filament-panels::page>
