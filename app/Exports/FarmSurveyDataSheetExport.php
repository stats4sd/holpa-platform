<?php

namespace App\Exports;

use App\Models\SurveyData\FarmSurveyData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;

class FarmSurveyDataSheetExport implements FromCollection, WithHeadings, WithTitle, WithMapping, WithStrictNullComparison
{
    public array $mainSurveyFields;

    public function __construct()
    {
        $mainSurveyTable = (new FarmSurveyData())->getTable();

        $this->mainSurveyFields = array_diff(DB::getSchemaBuilder()
            ->getColumnListing($mainSurveyTable), [
            'id',
            'created_at',
            'updated_at',
            'yeswomenhh', // not needed in output
            'farm_id', // manually added into the start of the output
            'final_location_id', // manually added into the start of the output
        ]);
    }

    public function collection(): Collection
    {
        return FarmSurveyData::with([
            'farm',
            'farm.location.locationLevel',
        ])->get();
    }

    public function map($row): array
    {
        $data = [
            $row->farm_id,

            // $row->farm->team_code,
            // $row->farm->location->locationLevel->name,
            // $row->farm->location->name,

            'farm_team_code',
            'farm_location_location_level_name',
            'farm_location_name',
        ];

        $mainSurveyData = $row->only($this->mainSurveyFields);

        return array_merge($data, $mainSurveyData);
    }

    public function headings(): array
    {
        return [
            'farm_id',
            'team_code',
            'location_level',
            'location_name',
            ...$this->mainSurveyFields,
        ];
    }

    public function title(): string
    {
        return 'Farm_Survey_Data';
    }
}
