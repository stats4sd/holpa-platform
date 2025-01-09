<div class="space-y-8">
    <p>HOLPA consists of 2 separate but related ODK Forms. Please review the translations for each form separately. To add or edit translations, please download the 2 Excel files. The files contain all the text required for the survey in English, and a column to enter the translations for {{ $locale->description }}.</p>

    <div class="flex w-full justify-around">
        <div class="w-1/4">
            {{ $this->downloadHouseholdAction }}
        </div>

        <div class="w-1/4">
            {{ $this->downloadFieldworkAction }}
        </div>
    </div>


</div>
