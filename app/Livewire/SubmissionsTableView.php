<?php

namespace App\Livewire;

use App\Filament\App\Resources\SubmissionResource;
use Couchbase\Group;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Contracts\HasAffixActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Tables\Actions\Action;
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
            ->defaultGroup(\Filament\Tables\Grouping\Group::make('xlsformVersion.xlsform.title')->label(''))
            ->query(fn() => Submission::whereHas('xlsformVersion',
                fn($query) => $query->whereHas('xlsform',
                    fn($query) => $query->where('owner_id', HelperService::getCurrentOwner()->id)
                )
            ))
            ->columns([
                TextColumn::make('xlsformVersion.version')->label('Xlsform Version'),
                TextColumn::make('survey_started_at'),
                TextColumn::make('survey_ended_at'),
                TextColumn::make('submitted_at')->label('Submitted at'),
                TextColumn::make('if_updated_at')
                    ->label('Updated at')

            ])
            ->actions([
                Action::make('view')
                    ->label('View Raw Data')
                    ->icon('heroicon-o-eye')
                    ->url(fn(Submission $record) => SubmissionResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
