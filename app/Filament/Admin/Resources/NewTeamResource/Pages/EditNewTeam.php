<?php

namespace App\Filament\Admin\Resources\NewTeamResource\Pages;

use App\Filament\Admin\Resources\NewTeamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewTeam extends EditRecord
{
    protected static string $resource = NewTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
