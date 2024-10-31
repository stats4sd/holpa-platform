<?php

namespace App\Filament\App\Resources\LocalIndicatorResource\Pages;

use App\Filament\App\Resources\LocalIndicatorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLocalIndicators extends ListRecords
{
    protected static string $resource = LocalIndicatorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
