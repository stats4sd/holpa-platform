<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomIndicatorExport implements FromCollection, WithHeadings
{
    protected $team;

    public function __construct($team)
    {
        $this->team = $team;
    }

    public function collection()
    {
        return collect($this->team->localIndicators()->where('is_custom', 1)->get())->map(function ($indicator) {
            return [
                'indicator' => $indicator->name,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Indicator',
        ];
    }
}
