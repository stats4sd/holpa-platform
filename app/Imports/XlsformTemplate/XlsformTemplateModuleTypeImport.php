<?php

namespace App\Imports\XlsformTemplate;

use App\Models\XlsformTemplateModuleType;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithUpserts;

class XlsformTemplateModuleTypeImport implements ToModel, WithUpserts, ShouldQueue, WithChunkReading, SkipsEmptyRows
{
    public function model(array $row): XlsformTemplateModuleType
    {
        return new XlsformTemplateModuleType([
            'name' => $row['module'],
            'label' => $row['module'],
            'updated_during_import' => true,
        ]);
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function uniqueBy(): array
    {
        return ['name'];
    }
}
