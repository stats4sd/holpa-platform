<?php

namespace App\Filament\App\Pages\Pilot;

use App\Filament\App\Pages\SurveyDashboard;
use App\Filament\App\Resources\SubmissionResource;
use App\Models\Team;
use App\Services\HelperService;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Xlsform;
use Stats4sd\FilamentOdkLink\Services\OdkLinkService;

class MainPilot extends Page implements HasTable, HasInfolists, HasActions
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithInfolists;
    use InteractsWithActions;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.main-pilot';
    protected ?string $heading = "Survey Testing - Pilot and Enumerator Training";
    protected ?string $subheading = "Test with enumerators; pilot with real farmers";

    public function getBreadcrumbs(): array
    {
        return [
            SurveyDashboard::getUrl() => 'Survey Dashboard',
            PilotIndex::getUrl() => 'Pilot',
            static::getUrl() => static::getTitle(),
        ];
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getRecord(): Team
    {
        return HelperService::getCurrentOwner();
    }

    public function viewSubmissionsAction(): Action
    {
        return Action::make('viewSubmissions')
            ->color('blue')
            ->extraAttributes(['class' => 'buttona'])
            ->url(SubmissionResource::getUrl('index'));
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
                TableAction::make('update_published_version')
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

                TableAction::make('pull-submissions')
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
