<?php

namespace App\Exports\DataExport;

use App\Models\Interfaces\RepeatModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;

class RepeatGroupExport implements FromQuery, WithHeadings, WithMapping, WithStrictNullComparison, WithTitle
{
    public string $title;

    public array $fields;

    public function __construct(
        public RepeatModel $model,
        public array $excludedColumns = ['id', 'created_at', 'updated_at', 'farm_survey_data_id'],
    ) {
        $tableName = (new $model)->getTable();

        $this->title = Str::title($tableName);

        // get all columns from a database table, exclude specific columns
        $this->fields = array_diff(DB::getSchemaBuilder()->getColumnListing($tableName), $excludedColumns);
    }

    public function query()
    {
        // add performance ID into the query so we can correctly get the related data;
        return $this->model::query()->select(array_merge($this->fields))
            ->with('farmSurveyData.farm');
    }

    public function title(): string
    {
        return $this->title;
    }

    public function headings(): array
    {
        $headings = collect($this->fields);

        $headings = $headings->prepend('farm_code');
        $headings = $headings->prepend('farm_id');
        $headings = $headings->prepend('farm_survey_data_id');

        return $headings->toArray();
    }

    public function map($row): array
    {
        $map = collect($this->fields)
            ->map(fn ($field) => $row[$field]);

        // $map = $map->prepend($row->mainSurvey->farm->team_code);
        // $map = $map->prepend($row->mainSurvey->farm_id);
        // $map = $map->prepend($row->farm_survey_data_id);

        $map = $map->prepend('farm_team_code');
        $map = $map->prepend('farm_location_location_level_name');
        $map = $map->prepend('farm_location_name');

        return $map->toArray();
    }
}
