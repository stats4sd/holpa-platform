<?php

namespace App\Filament\Admin\Resources\TeamResource\RelationManagers;

use Stats4sd\FilamentOdkLink\Models\OdkLink\Xlsform;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;
use Stats4sd\FilamentOdkLink\Exports\XlsformExport\XlsformWorkbookExport;
use Stats4sd\FilamentOdkLink\Services\OdkLinkService;

class XlsformsRelationManager extends \Stats4sd\FilamentTeamManagement\Filament\App\Resources\TeamResource\RelationManagers\XlsformsRelationManager
{


    public function table(Table $table): Table
    {
        return parent::table($table)
            ->actions([
                Action::make('download xls file')
                    ->action(function (Xlsform $record) {

                        return Excel::download((new XlsformWorkbookExport($record)), 'xlsform.xlsx');

                    }),
                // add Publish button
                Action::make('publish')
                    ->label('Publish')
                    ->icon('heroicon-m-arrow-up-tray')
                    ->requiresConfirmation()
                    ->action(function (Xlsform $record) {
                        $odkLinkService = app()->make(OdkLinkService::class);

                        // create draft if there is no draft yet
                        if (!$record->has_draft) {
                            $record->deployDraft($odkLinkService);
                        }

                        // call API to publish form in ODK central
                        $odkLinkService->publishForm($record);
                    }),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Xlsform to Team')
                    ->after(function (Xlsform $record) {

                        $odkLinkService = app()->make(OdkLinkService::class);

                        $record->refresh();
                        $record->deployDraft($odkLinkService);
                    }),
            ]);
    }
}
