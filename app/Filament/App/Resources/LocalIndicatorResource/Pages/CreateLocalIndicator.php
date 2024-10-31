<?php

namespace App\Filament\App\Resources\LocalIndicatorResource\Pages;

use App\Filament\App\Resources\LocalIndicatorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLocalIndicator extends CreateRecord
{
    protected static string $resource = LocalIndicatorResource::class;

    // add latest team Id to the submitted request form, the newly created model will belong to this team
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['team_id'] = auth()->user()->latestTeam->id;

        return $data;
    }
}
