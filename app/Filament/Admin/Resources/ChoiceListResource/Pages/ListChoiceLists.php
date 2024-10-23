<?php

namespace App\Filament\Admin\Resources\ChoiceListResource\Pages;

use App\Filament\Admin\Resources\ChoiceListResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChoiceLists extends ListRecords
{
    protected static string $resource = ChoiceListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
