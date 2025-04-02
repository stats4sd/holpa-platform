<div>

<div class="text-lg font-bold text-green pb-4 uppercase">
      Place custom questions in survey
    </div>
        
    <div class="text-black pb-4 mb-9">
    To add your custom questions into the survey, drag and drop each item into the correct place in the list on the right.
    </div>
    <div class="grid grid-cols-2 gap-4">

        <div
            class="space-y-2 py-4 pr-2"
            x-data
            x-sortable-source

        >

            <span class="text-base font-semibold ">Local Indicator Questions</span>

            @foreach($localIndicators as $indicator)

                <x-filament::section
                    :heading="$indicator->name"
                    x-sortable-item="{{ $indicator->xlsformModuleVersion->id }}"
                    class="draggable indicator"
                    collapsible
                    collapsed
                >

                    @foreach($indicator->xlsformModuleVersion->surveyRows as $surveyRow)

                        <div class="flex items-center indicator_questions">{{ $surveyRow->name }} <br>({{ $surveyRow->type }})</div>

                    @endforeach


                </x-filament::section>

            @endforeach
        </div>

        <div
            class="py-4 pl-2 space-y-2 "
        >
            @foreach($xlsforms as $xlsform)

            <span class="text-base font-semibold  ">{{ $xlsform->title }}</span>

                <div x-data
                     x-sortable-target
                     x-on:sorted="$wire.updateOrder($event.detail, '{{ $xlsform->id }}')"
                     class="!mb-8"
                >

                    @foreach($xlsform->xlsformModuleVersions as $xlsformModuleVersion)
                        <x-filament::section
                            :heading="$xlsformModuleVersion->name"
                            collapsible
                            collapsed
                            x-sortable-item="{{ $xlsformModuleVersion->id }}"
                            class=""
                            @class([
                            "rounded-none border indicator my-2",
                            "bg-gray-200 border-gray-500 global-module indicator" => $xlsformModuleVersion->owner?->id !== $team->id,
                            'bg-slate-200 border-slate-900 indicator my-2' => $xlsformModuleVersion->owner?->id === $team->id,
                            ])
                        >

                            @foreach($xlsformModuleVersion->surveyRows as $surveyRow)
                                <div class="flex items-center indicator_questions">{{ $surveyRow->name }} ( {{ $surveyRow->type }} )</div>
                            @endforeach

                        </x-filament::section>

                    @endforeach
                </div>
            @endforeach
        </div>

    </div>
<div class="w-100 text-center">
<a class ="buttonb" > Reset all </a>
</div>
</div>
