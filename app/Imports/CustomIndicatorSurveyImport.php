<?php

namespace App\Imports;

use App\Models\Xlsforms\Xlsform;
use Illuminate\Support\Collection;
use App\Models\Xlsforms\FormSurveyRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;

class CustomIndicatorSurveyImport implements ToModel, WithHeadingRow, WithUpserts, SkipsEmptyRows, WithChunkReading, ShouldQueue
{
    use RemembersRowNumber;

    public function __construct(public Xlsform $xlsform, public Collection $translatableHeadings) //check translatable headings
    {
    }

    public function model(array $row): FormSurveyRow
    {

        $row = collect($row);

        // get the columns that are part of the XLSform spec (but are not translatable columns like 'label')
        $data = $row
            ->filter(fn($value, $key) => in_array($key, $this->getSurveyRowHeaders()))
            ->filter(fn($value, $key) => $value !== null);

        // all non-xlsform-spec and non-translatable columns are considered properties and bundled as json.
        $props = $row
            ->filter(fn($value, $key) => !$this->translatableHeadings->contains($key))
            ->filter(fn($value, $key) => !in_array($key, $this->getSurveyRowHeaders()))
            ->filter(fn($value, $Key) => $value !== null);

        $data['properties'] = $props;
        $data['xlsform_id'] = $this->xlsform->id;

        // for end_group or end_repeats, the name might be empty.
        // In that case, we generate a unique name based on the type.
        if (!isset($data['name']) || $data['name'] === '') {
            $data['name'] = $data['type'] . '_' . $this->getRowNumber();
        }

        return new FormSurveyRow($data->toArray());

    }

    private function getSurveyRowHeaders(): array
    {
        return
            [
                'name',
                'type',
                'required',
                'relevant',
                'appearance',
                'calculation',
                'constraint',
                'choice_filter',
                'repeat_count',
                'default',
                'note',
                'trigger',
            ];
    }

    public function uniqueBy(): array
    {
        return ['xlsform_id', 'name', 'type'];
    }

    public function isEmptyWhen(array $row): bool
    {
        return (!isset($row['name']) || $row['name'] === '')
            && (!isset($row['type']) || $row['type'] === '');

    }

    public function chunkSize(): int
    {
        return 500;
    }

}
