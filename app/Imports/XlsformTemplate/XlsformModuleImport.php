<?php

namespace App\Imports\XlsformTemplate;

use App\Models\Xlsforms\XlsformModule;
use App\Models\Xlsforms\XlsformTemplate;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

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
        ];
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection): void
    {
        // for any rows that do not have a module set... add them to a 'default' module
        // (this also handles the case where the xlsform file does not have a module column)
        $collection = $collection
            ->map(function ($row) {
                if(!isset($row['module'])) {
                    $row['module'] = Str::slug($this->xlsformTemplate->title) . '_main';
                }

                return $row;
            });

        // now all entries have a module, process them:
        $collection
            ->each(function ($row) {

            // make sure xlsformModule exists

            /** @var XlsformModule $module */
            $module = $this->xlsformTemplate->xlsformModules()->firstOrCreate([
                'name' => $row['module'],
                'label' => $row['module'], // can be edited later in the platform
            ]);
        });
    }
}
