<?php

namespace App\Filament\Admin\Resources\NewTeamResource\Pages;

use App\Filament\Admin\Resources\NewTeamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewTeams extends ListRecords
{
    protected static string $resource = NewTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
