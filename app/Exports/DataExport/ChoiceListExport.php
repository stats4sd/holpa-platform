<?php

namespace App\Exports\DataExport;

use App\Models\XlsformTemplates\ChoiceListEntry;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ChoiceListExport implements FromQuery, WithHeadings, WithMapping
{

    public function __construct()
    {
    }

    public function query()
    {
        ChoiceListEntry::query();
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
            'list_name',
            'name',
            'label_en',
            'indicator_value',
            'climate_pillar',
        ];

    }
}
