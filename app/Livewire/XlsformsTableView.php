<?php

namespace App\Livewire;

use App\Services\HelperService;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Livewire\Component;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Xlsform;
use Stats4sd\FilamentOdkLink\Services\OdkLinkService;

class XlsformsTableView extends Component implements HasTable, HasActions, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithActions;

    public function render()
    {
        return view('livewire.xlsforms-table-view');
    }

     public function table(Table $table): Table
    {
        return $table
            ->relationship(fn(): HasMany => HelperService::getCurrentOwner()->xlsforms())
            ->inverseRelationship('owner')
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->grow(false),
                TextColumn::make('status')
                    ->color(fn($state) => match ($state) {
                        'UPDATES AVAILABLE' => 'danger',
                        'LIVE' => 'success',
                        'DRAFT' => 'info',
                        default => 'light',
                    })
                    ->iconColor(fn($state) => match ($state) {
                        'UPDATES AVAILABLE' => 'danger',
                        'LIVE' => 'success',
                        'DRAFT' => 'info',
                        default => 'light',
                    })
                    ->icon(fn($state) => match ($state) {
                        'UPDATES AVAILABLE' => 'heroicon-o-exclamation-circle',
                        'LIVE' => 'heroicon-o-check',
                        'DRAFT' => 'heroicon-o-pencil',
                        default => 'heroicon-o-information-circle',
                    })
                    ->label('Status'),

                TextColumn::make('live_submissions_count')
                    ->label('No. of Submissions'),
                TextColumn::make('submissions_count')
                    ->label('Submissions in database')
                    ->counts('submissions'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('update_published_version')
                    //->visible(fn(Xlsform $record) => !$record->has_latest_template)
                    ->label('Deploy Updates')
                    ->action(function (Xlsform $record) {

                        $record->syncWithTemplate();
                        $record->deployDraft(app()->make(OdkLinkService::class));
                        $record->refresh();

                        Notification::make('update_success')
                            ->title('Success!')
                            ->body("The form {$record->title} has the latest updates and is ready for testing")
                            ->color('success')
                            ->send();
                    }),

                Action::make('pull-submissions')
                    ->label('Manually Get Submissions')
                    ->action(function (Xlsform $record) {
                        $submissionCount = $record->getSubmissions();


                        $record->refresh();
                        Notification::make('update_success')
                            ->title('Success!')
                            ->body("{$submissionCount} submissions have been pulled from the ODK server for the form {$record->title} (they may take a moment to process).")
                            ->color('success')
                            ->send();
                    }),

            ])
            ->bulkActions([]);
    }
}
