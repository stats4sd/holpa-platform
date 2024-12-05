<?php

namespace App\Imports;

use App\Models\Team;
use App\Models\LocalIndicator;
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
                'theme_id' => $row['theme_id'],
                'team_id' => $this->team->id,
            ]);
        }
    }

}