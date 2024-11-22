<?php

namespace App\Exports\XlsformExport;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Xlsform;

class XlsformChoicesExport implements FromCollection
{
    public function __construct(public Xlsform $xlsform)
    {
    }

    public function collection()
    {
        // for each choice list, go through the choices...

        dd($this->xlsform);

        dd($choices);

    }
}
