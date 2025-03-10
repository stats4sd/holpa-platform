<div>
    <div class="grid grid-cols-2 gap-4">

        <div
            class="p-4 space-y-2"
            x-data
            x-sortable-source
            x-on:sorted="console.log($event.detail)"

        >

            <h2>Local Indicator Questions</h2>
            <div class="bg-info-100 rounded-none p-7 border">
                To add your custom questions into the survey, drag and drop each item into the correct place in the list on the right.
            </div>
            @foreach($localIndicators as $indicator)
                <x-filament::section :heading="$indicator->name" x-sortable-item="{{ $indicator->id }}" class="bg-slate-200 rounded-none border-slate-900 border">

                    @foreach($indicator->xlsformModuleVersion->surveyRows as $surveyRow)

                        <div class="flex items-center">{{ $surveyRow->name }} ( {{ $surveyRow->type }} )</div>

                    @endforeach


                </x-filament::section>
            @endforeach
        </div>

        <div
            class="p-4 space-y-2"
            x-data
            x-sortable-target
            x-on:sorted="console.log($event.detail)"
        >
            @foreach($xlsformTemplates as $xlsformTemplate)

                <h2>{{ $xlsformTemplate->title }}</h2>

                @foreach($xlsformTemplate->xlsformModules as $xlsformModule)

                    <x-filament::section :heading="$xlsformModule->name" collapsible collapsed x-sortable-item="{{ $xlsformModule->id }}" class="bg-gray-200 rounded-none border-gray-800 border global-module">

                        @foreach($xlsformModule->defaultXlsformVersion->surveyRows as $surveyRow)
                            <div class="flex items-center">{{ $surveyRow->name }} ( {{ $surveyRow->type }} )</div>
                        @endforeach


                    </x-filament::section>

                @endforeach

            @endforeach
        </div>

    </div>


</div>
