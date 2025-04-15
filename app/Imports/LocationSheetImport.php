<?php

namespace App\Imports;

use App\Models\SampleFrame\Location;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class LocationSheetImport implements ShouldQueue, SkipsEmptyRows, ToCollection, WithCalculatedFormulas, WithChunkReading, WithHeadingRow, WithStrictNullComparison
{
    protected Collection $parentIds;

    public function __construct(public array $data)
    {
        $data['code_column'] = $data['header_columns'][$data['code_column']];
        $data['name_column'] = $data['header_columns'][$data['name_column']];

        $keys = collect(array_keys($data));

        $parentColumns = $keys->filter(fn ($key) => str_starts_with($key, 'parent_'));

        foreach ($parentColumns as $parentColumn) {
            $data[$parentColumn] = $data['header_columns'][$data[$parentColumn]] ?? null;
        }

        $this->parentIds = $parentColumns->filter(fn ($key) => str_contains($key, '_code'))
            ->map(fn ($key) => str_replace(['parent_', '_code_column'], '', $key));

        $this->data = $data;
    }

    public function collection(Collection $rows): array
    {
        $locationLevel = $this->data['level'];

        $importedLocations = [];

        foreach ($rows as $row) {

            $currentParent = null;

            // go through parents in order from highest to lowest. Ensure all parents exist in the database (and create them if they do not)
            foreach ($this->parentIds as $parentId) {
                Location::upsert(
                    values: [
                        'owner_id' => $this->data['owner_id'],
                        'code' => $row[$this->data["parent_{$parentId}_code_column"]],
                        'name' => $row[$this->data["parent_{$parentId}_name_column"]],
                        'location_level_id' => $parentId,
                        'parent_id' => $currentParent?->id,
                    ],
                    uniqueBy: 'code'
                );

                $currentParent = Location::where('code', $row[$this->data["parent_{$parentId}_code_column"]])->first();
            }

            // Create the location if it doesn't already exist
            Location::upsert(
                values: [
                    'owner_id' => $this->data['owner_id'],
                    'location_level_id' => $locationLevel->id,
                    'parent_id' => $currentParent?->id ?? null,
                    'code' => $row[$this->data['code_column']],
                    'name' => $row[$this->data['name_column']],
                ],
                uniqueBy: 'code'
            );

            $currentLocation = Location::where('code', $row[$this->data['code_column']])->first();

            $importedLocations[] = $currentLocation;
        }

        return $importedLocations;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
