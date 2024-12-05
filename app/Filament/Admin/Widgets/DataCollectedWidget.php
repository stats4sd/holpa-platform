<?php

namespace App\Filament\Admin\Widgets;

use App\Models\XlsformTemplate;
use App\Filament\Admin\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\HtmlString;
use Filament\Widgets\StatsOverviewWidget;

class DataCollectedWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Data collected';

    protected function getStats(): array
    {
        $result = [];

        // TODO: find number of farms that completed both household form and fieldwork form
        // we need an easy way to find out how many farms have completed both forms
        // E.g. SELECT COUNT(*) FROM farms WHERE household_form_completed = 1 AND fieldwork_form_completed = 1;

        // use hardcode value as a placeholder temporary
        $farmsSurveyed = 0;

        array_push($result, Stat::make(new HtmlString('Farms surveyed (TODO)'), $farmsSurveyed));


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
