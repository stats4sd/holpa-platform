<?php

namespace App\Imports\XlsformTemplate;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class XlsformTemplateModuleImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
    }
}
