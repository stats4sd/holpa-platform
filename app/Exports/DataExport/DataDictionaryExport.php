<?php

namespace App\Exports\DataExport;

use App\Models\DataDictionaryEntry;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class DataDictionaryExport implements FromQuery, WithMapping, WithHeadings, WithTitle
{

    public function __construct()
    {
    }

    public function query(): Builder
    {
        return DataDictionaryEntry::query();
    }

    public function map($row): array
    {

        return [
            $row['worksheet'],
            $row['variable'],
            $row['theme'],
            $row['survey_section'],
            $row['indicator_number'],
            $row['indicator_name'],
            $row['question_or_definition'],
            $row['type'],
            $row['code_list'],
            $row['multiple_choice_options_label'],
        ];
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

    public function title(): string
    {
        return 'Data_Dictionary';
    }
}
