<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CustomIndicatorExport implements WithMultipleSheets
{
    protected $team;

    public function __construct($team)
    {
        $this->team = $team;
    }

    public function sheets(): array
    {
        return [
            new CustomIndicatorSurveySheet($this->team),
            new CustomIndicatorChoicesSheet($this->team),
        ];
    }
}
