<?php

namespace App\Livewire;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Contracts\HasAffixActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Submission;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Xlsform;
use Stats4sd\FilamentOdkLink\Services\HelperService;

class SubmissionsTableView extends Component implements HasTable, HasActions, HasForms
{
    use InteractsWithForms;
    use InteractsWithTable;
    use InteractsWithActions;

    public function render()
    {
        return view('livewire.submissions-table-view');
    }


    public function table(Table $table): Table
    {
        return $table
            ->query(fn() => Submission::whereHas('xlsformVersion',
                fn($query) => $query->whereHas('xlsform',
                    fn($query) => $query->where('owner_id', HelperService::getCurrentOwner()->id)
                )
            ))
            ->columns([
                TextColumn::make('odk_id')->label('uuid'),
                TextColumn::make('xlsformVersion.xlsform.title')->label('Form'),
                TextColumn::make('xlsformVersion.version')->label('Version'),
                TextColumn::make('survey_started_at'),
                TextColumn::make('survey_ended_at'),
                TextColumn::make('submitted_at')->label('Submitted at'),
            ]);
    }
}
