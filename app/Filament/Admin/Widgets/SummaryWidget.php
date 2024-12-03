<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Team;
use App\Models\User;
use App\Models\XlsformTemplate;
use App\Models\SampleFrame\Farm;
use App\Filament\Admin\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\HtmlString;
use Filament\Widgets\StatsOverviewWidget;
use Stats4sd\FilamentTeamManagement\Models\Program;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Xlsform;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Submission;

class SummaryWidget extends StatsOverviewWidget
{

    protected function getStats(): array
    {
        $result = [];

        // find total number of records for different entities
        array_push($result, Stat::make(new HtmlString('Programs'), Program::count()));
        array_push($result, Stat::make(new HtmlString('Teams'), Team::count()));
        array_push($result, Stat::make(new HtmlString('Users'), User::count()));
        array_push($result, Stat::make(new HtmlString('Farms'), Farm::count()));
        array_push($result, Stat::make(new HtmlString('Xlsform Templates'), XlsformTemplate::count()));
        array_push($result, Stat::make(new HtmlString('Xlsforms'), Xlsform::count()));
        array_push($result, Stat::make(new HtmlString('Submissions'), Submission::count()));


        // find total number of submissions for each xlsform template
        $xlsformTemplates = XlsFormTemplate::all();

        foreach ($xlsformTemplates as $xlsformTemplate) {
            $total = 0;
            foreach ($xlsformTemplate->xlsforms as $xlsform) {
                foreach ($xlsform->xlsformVersions as $xlsformVersion) {
                    $total = $total + $xlsformVersion->submissions->count();
                }
            }

            array_push($result, Stat::make(new HtmlString('Submissions for Xlsform Template -<br/>' . $xlsformTemplate->title), $total));
        }

        return $result;
    }
}
