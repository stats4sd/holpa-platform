<?php

namespace App\Exports\XlsformExport;

use App\Models\Xlsforms\Xlsform;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class XlsformSettingsExport implements FromCollection, WithHeadings, WithTitle
{

    public function __construct(public Xlsform $xlsform)
    {
    }

    public function collection(): \Illuminate\Support\Collection
    {
        // needs to be in the format
        // | form_id | form_title | version | instance_name
        // | ------- | ---------- | ------- | -------------
        // | {{formId}}       | {{formTitle}}     | {{datetime}}     | ${team_code}_${template_code}_${version}

        return collect([
            [
                'form_id' => $this->xlsform->odk_id ?? Str::slug($this->xlsform->title),
                'form_title' => $this->xlsform->title,
                'version' => Carbon::now()->toDateTimeString(),
                'instance_name' => 'TO BE UPDATED!!', // TODO: fix
            ],
        ]);

    }

    public function headings(): array
    {
        return [
            'form_id',
            'form_title',
            'version',
            'instance_name',
        ];
    }

    public function title(): string
    {
        return 'settings';
    }
}
