<?php

namespace App\Imports;

use App\Models\SampleFrame\Farm;
use App\Models\SampleFrame\Location;
use App\Models\SampleFrame\LocationLevel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithValidation;

class FarmSheetImport implements ShouldQueue, SkipsEmptyRows, ToCollection, WithCalculatedFormulas, WithChunkReading, WithHeadingRow, WithStrictNullComparison, WithValidation
{
    // The $data array is the data that is passed from the ImportFarmsAction form
    public function __construct(public array $data) {}

    public function collection(Collection $rows): array
    {
        $importedFarms = [];

        foreach ($rows as $row) {
            $headers = $this->data['header_columns'];

            $farmCodeColumn = $headers[$this->data['farm_code_column']];
            $locationLevel = LocationLevel::find($this->data['location_level_id']);
            $locationCodeColumn = $headers[$this->data['location_code_column']];
            $location = Location::where('code', $row[$locationCodeColumn])
                ->where('location_level_id', $locationLevel->id)
                ->first();

            // Find the identifier columns;
            $identifierColumns = collect($this->data['farm_identifiers'])->map(fn ($identifier) => $headers[$identifier]);
            // Get the data from those columns;
            $identifierData = $identifierColumns->mapWithKeys(fn ($column) => [$column => $row[$column]]);

            // Find the property columns;
            $propertyColumns = collect($this->data['farm_properties'])->map(fn ($property) => $headers[$property]);
            // Get the data from those columns;
            $propertyData = $propertyColumns->mapWithKeys(fn ($column) => [$column => $row[$column]]);

            // Create the farm
            $farm = new Farm([
                'owner_id' => $this->data['owner_id'],
                'location_id' => $location->id,
                'team_code' => $row[$farmCodeColumn],
                'identifiers' => $identifierData,
                'properties' => $propertyData,
            ]);
            $farm->save();

            $importedFarms[] = $farm;
        }

        return $importedFarms;
    }

    public function rules(): array
    {
        $headers = $this->data['header_columns'];
        $locationCodeColumn = $headers[$this->data['location_code_column']];
        $farmCodeColumn = $headers[$this->data['farm_code_column']];

        return [
            $locationCodeColumn => 'required|exists:locations,code',
            $farmCodeColumn => 'required',
        ];
    }

    public function customValidationMessages(): array
    {
        $headers = $this->data['header_columns'];
        $locationCodeColumn = $headers[$this->data['location_code_column']];
        $farmCodeColumn = $headers[$this->data['farm_code_column']];

        return [
            "$locationCodeColumn.required" => "The $locationCodeColumn cannot be empty.",
            "$locationCodeColumn.exists" => 'The location with this code does not exist in the database.',
            "$farmCodeColumn.required" => 'The farm code cannot be empty.',
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
