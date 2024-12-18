<?php

namespace App\Imports\XlsformTemplate;

use App\Models\XlsformModule;
use App\Models\XlsformTemplates\XlsformTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithUpserts;

class XlsformModuleImport implements ToCollection, WithHeadingRow, SkipsEmptyRows, WithMultipleSheets
{

    use Importable;

    public function __construct(public XlsformTemplate $xlsformTemplate)
    {
    }

    public function sheets(): array
    {
        return [
            'survey' => $this,
            'choices' => $this, // for now, choice lists from the global surveys are all linked to 'introduction'.
        ];
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $collection->each(function ($row) {
            // make sure xlsformModule exists



            /** @var XlsformModule $module */
            $module = $this->xlsformTemplate->xlsformModules()->firstOrCreate([
                'name' => $row['module'],
                'label' => $row['module'], // can be edited later in the platform
            ]);

            // make sure xlsformModuleVersion exists, so we can import the survey rows etc
            $module->xlsformModuleVersions()->firstOrCreate([
                'name' => 'Global ' . $row['module'], // hard-code name for now
                'is_default' => true,
            ]);
        });
    }
}
