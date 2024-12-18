<?php

namespace App\Imports\XlsformTemplate;

use App\Models\Interfaces\WithXlsformFile;
use App\Models\XlsformModuleVersion;
use App\Models\XlsformTemplates\SurveyRow;
use App\Models\XlsformTemplates\XlsformTemplate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class XlsformTemplateSurveyImport implements ToModel, WithHeadingRow, WithUpserts, SkipsEmptyRows, WithChunkReading, ShouldQueue
{
    use RemembersRowNumber;

    public function __construct(public WithXlsformFile $xlsformTemplate, public Collection $translatableHeadings)
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

        $data['row_number'] = $this->getRowNumber(); // make sure ordering from file is preserved even when it's changed since the first upload
        $data['properties'] = $props;

        // if we are importing an entire template, the "template" id and type should be based on the $row['module'] import
        // TODO: fix these confusing relation names!

        if ($this->xlsformTemplate instanceof XlsformTemplate) {
            $xlsformModuleVersion = $this->getXlsformModuleVersion($row['module'] ?? null);
        } else {
            $xlsformModuleVersion = $this->xlsformTemplate;
        }

        $data['template_id'] = $xlsformModuleVersion->id;
        $data['template_type'] = get_class($xlsformModuleVersion);
        $data['updated_during_import'] = true; // to make sure we don't delete this row after import.

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
        return ['template_id', 'template_type', 'name', 'type'];
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


    public function getXlsformModuleVersion(string $name): XlsformModuleVersion
    {

        $xlsformModuleVersion = $this->xlsformTemplate->xlsformModuleVersions()
            // get the default module version...
            ->where('is_default', true)
            // ... of the module that matches the $row['module'] name
            ->whereHas('xlsformModule', fn(Builder $query) => $query->where('name', $name))
            ->first();

        if (!$xlsformModuleVersion) {
            dd("module version not found for {$name}");
        }

        return $xlsformModuleVersion;
    }


}
