<?php

namespace App\Imports\XlsformTemplate;

use App\Models\Interfaces\WithXlsformFile;
use App\Models\XlsformTemplates\ChoiceListEntry;
use App\Models\XlsformTemplates\XlsformTemplate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class XlsformTemplateChoicesImport implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue, SkipsEmptyRows, WithUpserts
{

    use RemembersRowNumber;

    public function __construct(public WithXlsformFile $xlsformTemplate, public Collection $translatableHeadings)
    {
    }

    public function model(array $row): ChoiceListEntry
    {
        $row = collect($row);
        $data = [
            'name' => $row['name'],
        ];

        $data['choice_list_id'] = $this->xlsformTemplate
            ->choiceLists()
            ->where('list_name', $row['list_name'])
            ->first()
            ->id;

        $data['properties'] = $row
            ->filter(fn($value, $key) => !$this->translatableHeadings->contains($key))
            ->filter(fn($value, $key) => $key !== 'name')
            ->filter(fn($value, $key) => $key !== 'list_name')
            ->filter(fn($value, $key) => $value !== null);

        $data['updated_during_import'] = true;

        return new ChoiceListEntry($data);
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
