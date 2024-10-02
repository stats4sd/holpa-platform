<?php

namespace App\Filament\Program\Resources\TeamResource\Pages;

use App\Filament\Program\Resources\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeam extends EditRecord
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
