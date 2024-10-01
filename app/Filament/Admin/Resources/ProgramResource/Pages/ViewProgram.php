<?php

namespace App\Filament\Admin\Resources\ProgramResource\Pages;

use App\Filament\Admin\Resources\ProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewProgram extends ViewRecord
{
    protected static string $resource = ProgramResource::class;

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
