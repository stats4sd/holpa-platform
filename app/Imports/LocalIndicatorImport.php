<?php

namespace App\Imports;

use App\Models\Holpa\LocalIndicator;
use App\Models\Team;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LocalIndicatorImport implements WithMultipleSheets, ToCollection, WithHeadingRow
{
    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    public function sheets(): array
    {
        return [
            'indicators' => $this,
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            LocalIndicator::create([
                'name' => $row['name'],
                'domain_id' => $row['domain_id'],
                'team_id' => $this->team->id,
            ]);
        }
    }

}
