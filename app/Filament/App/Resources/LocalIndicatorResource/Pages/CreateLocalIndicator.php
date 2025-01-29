<?php

namespace App\Filament\App\Resources\LocalIndicatorResource\Pages;

use App\Filament\App\Resources\LocalIndicatorResource;
use App\Services\HelperService;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateLocalIndicator extends CreateRecord
{
    protected static string $resource = LocalIndicatorResource::class;

    // add latest team ID to the submitted request form, the newly created model will belong to this team
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['team_id'] = HelperService::getSelectedTeam()->id;

        return $data;
    }
}
