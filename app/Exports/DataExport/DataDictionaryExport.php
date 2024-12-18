<?php

namespace App\Exports\DataExport;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;

class DataDictionaryExport implements FromQuery, WithMapping, WithHeadingRow
{

    public function __construct()
    {
    }

    public function query(): Builder
    {
        return DataDictionaryExport::query();
    }

    public function map($row): array
    {
        $data = [];

        foreach($this->headings() as $heading) {
            $data[$heading] = $row->$heading;
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'worksheet',
            'variable',
            'theme',
            'survey_section',
            'indicator_number',
            'indicator_name',
            'question_or_definition',
            'type',
            'code_list',
            'multiple_choice_options_label'
        ];
    }
}
