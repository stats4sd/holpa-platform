<?php

namespace App\Filament\Admin\Resources\NewTeamResource\Pages;

use App\Filament\Admin\Resources\NewTeamResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewNewTeam extends ViewRecord
{
    protected static string $resource = NewTeamResource::class;

    public function getTitle(): string|Htmlable
    {
        return $this->getRecord()->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
