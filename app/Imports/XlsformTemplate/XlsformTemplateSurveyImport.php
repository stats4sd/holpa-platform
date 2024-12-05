<?php

namespace App\Imports\XlsformTemplate;

use App\Imports\HasTranslatableColumns;
use App\Models\LanguageString;
use App\Models\SurveyRow;
use App\Models\XlsformTemplate;
use App\Models\XlsformTemplateLanguage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class XlsformTemplateSurveyImport implements ToModel, WithHeadingRow, WithUpserts, SkipsEmptyRows, WithChunkReading, ShouldQueue
{
    use HasTranslatableColumns;
    use RemembersRowNumber;

    public function __construct(public XlsformTemplate $xlsformTemplate, public Collection $translatableHeadings)
    {
    }

    public function model(array $row): SurveyRow
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
        $data['xlsform_template_id'] = $this->xlsformTemplate->id;

        // for end_group or end_repeats, the name might be empty.
        // In that case, we generate a unique name based on the type.
        if (!isset($data['name']) || $data['name'] === '') {
            $data['name'] = $data['type'] . '_' . $this->getRowNumber();
        }

        return new SurveyRow($data->toArray());

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
        return ['xlsform_template_id', 'name'];
    }

    public function isEmptyWhen(array $row): bool
    {
        return $row['name'] === '' || $row['type'] === '';
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
