<?php

namespace App\Exports\DataExport;

use App\Models\Dataset;
use App\Models\Team;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class FarmSurveyDatasetExport implements FromCollection, WithHeadings, WithTitle
{
    public array $headings;

    public function __construct(public Team $team, public Dataset $dataset)
    {
        $this->headings = $this->dataset->variables()
            ->orderBy('order')
            ->pluck('name')->toArray();
    }

    public function collection(): Collection
    {
        // Fetch all entity IDs for this team and dataset
        $entityIds = DB::table('entities')
            ->where('owner_id', $this->team->id)
            ->where('dataset_id', $this->dataset->id)
            ->pluck('id');

        if ($entityIds->isEmpty()) {
            return collect();
        }

        // Fetch all entity values in a single query and group by entity_id
        $allValues = DB::table('entity_values')
            ->whereIn('entity_id', $entityIds)
            ->get(['entity_id', 'dataset_variable_name', 'value'])
            ->groupBy('entity_id');

        // Build the result set by pivoting in PHP with O(1) lookups
        return $entityIds->map(function ($entityId) use ($allValues) {

            $entityValues = $allValues->get($entityId, collect());

            // Create a keyed lookup for this entity's values
            $valuesMap = $entityValues->keyBy('dataset_variable_name');

            // Build the row using the keyed map (O(1) per heading)
            $row = [];
            foreach ($this->headings as $heading) {
                $row[$heading] = $valuesMap->get($heading)?->value;
            }

            return $row;
        });
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function title(): string
    {
        return 'Farm Survey Data';
    }
}
