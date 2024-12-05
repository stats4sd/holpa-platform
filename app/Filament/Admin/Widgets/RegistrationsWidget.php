<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Team;
use App\Models\User;
use App\Models\XlsformTemplate;
use App\Filament\Admin\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\HtmlString;
use Filament\Widgets\StatsOverviewWidget;
use Stats4sd\FilamentTeamManagement\Models\Program;

class RegistrationsWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Registrations';

    protected function getStats(): array
    {
        $result = [];

        // find total number of records for different entities
        array_push($result, Stat::make(new HtmlString('Programs'), Program::count()));
        array_push($result, Stat::make(new HtmlString('Teams'), Team::count()));
        array_push($result, Stat::make(new HtmlString('Users'), User::count()));

        return $result;
    }
}
