
<x-filament-widgets::widget>

    <x-filament::section class="mb-4" :heading="$choiceListName">
        {{ $choiceList->description ?? "The list below includes a set of possible responses to some of the survey questions. Please review the list and make sure it is appropriate for your context. You may add new entries and remove existing entries if they are not relevant."}}
    </x-filament::section>
    <x-filament::section collapsed heading="Questions that use this list within HOLPA" icon="heroicon-o-information-circle" collapsible>

        <ul>
            <li class="flex w-full border-b mb-2">
                <span class="w-1/4 text-right font-bold pr-4">Variable Name:</span>
                <span class="w-3/4 font-bold">Question text</span>
        @foreach($surveyRows as $surveyRow)
            <li class="flex w-full mb-2">
                <span class="w-1/4 text-right pr-4">{{ $surveyRow['name'] }}:</span>
                <span class="w-3/4">{!! \Illuminate\Support\Str::markdown($surveyRow['label']) !!}</span>
            </li>
        @endforeach

        </ul>
    </x-filament::section>


</x-filament-widgets::widget>
