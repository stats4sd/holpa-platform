<?php

namespace App\Filament\App\Clusters\LocationLevels\Resources\FarmResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\App\Clusters\LocationLevels\Resources\FarmResource;

class EditFarm extends EditRecord
{
    protected static string $resource = FarmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
