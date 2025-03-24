<x-filament-panels::page
    @class([
        'fi-resource-list-records-page',
        'fi-resource-' . str_replace('/', '-', $this->getResource()::getSlug()),
    ])
>


    {{--    TODO: DAVE START HERE AGAIN--}}
    <table class="table-auto w-full ">
        <tbody>

        @foreach($this->surveyRowData as $surveyRow)
            <tr>
                <td>
                    ( {{ $surveyRow['name'] }} )
                </td>
                <td>
                    {{ $surveyRow['type'] }}
                </td>
                <td>
                    {{ (is_array($surveyRow['value'])) ? json_encode($surveyRow['value']) : $surveyRow['value'] }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</x-filament-panels::page>
