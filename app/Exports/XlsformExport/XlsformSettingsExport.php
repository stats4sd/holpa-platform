<?php

namespace App\Exports\XlsformExport;

use App\Models\Xlsforms\Xlsform;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class XlsformSettingsExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{

    public function __construct(public Xlsform $xlsform)
    {
    }

    public function collection(): Collection
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
                'instance_name' => '"instance"', // TODO: fix
                'allow_choice_duplicates' => 'yes'
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
            'allow_choice_duplicates',
        ];
    }

    public function title(): string
    {
        return 'settings';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
