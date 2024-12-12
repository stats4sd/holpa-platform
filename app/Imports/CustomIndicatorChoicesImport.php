<?php

namespace App\Imports;

use App\Models\Xlsforms\Xlsform;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithUpserts;
use App\Models\Xlsforms\FormChoiceListEntry;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;

class CustomIndicatorChoicesImport implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue, SkipsEmptyRows, WithUpserts
{

    use RemembersRowNumber;

    public function __construct(public Xlsform $xlsform, public Collection $translatableHeadings)
    {
    }

    public function model(array $row): FormChoiceListEntry
    {
        $row = collect($row);
        $data = [
            'name' => $row['name'],
        ];

        $data['choice_list_id'] = $this->xlsform
            ->formChoiceLists
            ->where('list_name', $row['list_name'])
            ->first()
            ->id;

        $data['properties'] = $row
            ->filter(fn($value, $key) => !$this->translatableHeadings->contains($key))
            ->filter(fn($value, $key) => $key !== 'name')
            ->filter(fn($value, $key) => $key !== 'list_name')
            ->filter(fn($value, $key) => $value !== null);

        return new FormChoiceListEntry($data);
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function uniqueBy(): array
    {
        return ['name', 'choice_list_id'];
    }

    public function isEmptyWhen(array $row): bool
    {
        return (!isset($row['name']) || $row['name'] === '')
            || (!isset($row['list_name']) || $row['list_name'] === '');
    }

}

