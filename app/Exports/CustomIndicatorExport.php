<?php

namespace App\Exports;

use App\Models\Team;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CustomIndicatorExport implements WithMultipleSheets
{
    protected Team $team;
    protected string $surveyType;

    public function __construct($team, $surveyType)
    {
        $this->team = $team;
        $this->surveyType = $surveyType;
    }

    public function sheets(): array
    {
        return [
            new CustomIndicatorSurveySheet($this->team, $this->surveyType),
            new CustomIndicatorChoicesSheet($this->team),
        ];
    }
}
