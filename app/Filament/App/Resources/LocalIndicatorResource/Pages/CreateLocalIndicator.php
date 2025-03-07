<?php

namespace App\Filament\App\Resources\LocalIndicatorResource\Pages;

use App\Filament\App\Resources\LocalIndicatorResource;
use Filament\Resources\Pages\CreateRecord;
use App\Services\HelperService;

class CreateLocalIndicator extends CreateRecord
{
    protected static string $resource = LocalIndicatorResource::class;

    // add latest team ID to the submitted request form, the newly created model will belong to this team
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['team_id'] = HelperService::getCurrentOwner()->id;

        return $data;
    }
}
