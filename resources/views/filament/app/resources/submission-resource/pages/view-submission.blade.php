<x-filament-panels::page
    @class([
        'fi-resource-list-records-page',
        'fi-resource-' . str_replace('/', '-', $this->getResource()::getSlug()),
    ])
>


    {{--    TODO: DAVE START HERE AGAIN--}}
    <table class="table-auto w-full ">
        <tbody>

        @foreach($this->surveyRows as $surveyRow)
            <tr>
                <td>
                    ( {{ $surveyRow->name }} )
                </td>
                <td>
                    {{ $surveyRow->type }}
                </td>
                <td>
                    {{ $surveyRow->default_label }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</x-filament-panels::page>
