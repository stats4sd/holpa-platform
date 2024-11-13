<?php

namespace App\Imports;

use App\Models\XlsformTemplate;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class XlsformTemplateChoicesImport implements ToCollection
{

    public function __construct(public XlsformTemplate $xlsformTemplate)
    {
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
    }
}
