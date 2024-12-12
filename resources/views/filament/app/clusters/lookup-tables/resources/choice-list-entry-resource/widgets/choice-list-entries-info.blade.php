<x-filament-widgets::widget>
    <x-filament::section class="mb-4">
        The list below will be included in the questionnaire as a choice list. You can add, edit, and delete entries here, and the list will be updated in the questionnaire automatically.

    </x-filament::section>
    <x-filament::section collapsed heading="Questions that use this list within HOLPA" icon="heroicon-o-information-circle" collapsible>

        <ul>

            <li class="flex w-full border-b">
                <span class="w-1/4 text-right font-bold pr-4">Variable Name:</span>
                <span class="w-3/4 font-bold">Question text</span>
        @foreach($surveyRows as $surveyRow)
            <li class="flex w-full">
                <span class="w-1/4 text-right pr-4">{{ $surveyRow['name'] }}:</span>
                <span class="w-3/4">{{ $surveyRow['label'] }}</span>
            </li>
        @endforeach

        </ul>
    </x-filament::section>


</x-filament-widgets::widget>
