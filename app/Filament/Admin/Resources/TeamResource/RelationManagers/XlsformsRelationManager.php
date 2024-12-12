<?php

namespace App\Filament\Admin\Resources\TeamResource\RelationManagers;

use App\Exports\XlsformExport\XlsformWorkbookExport;
use App\Models\Xlsforms\Xlsform;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;
use Stats4sd\FilamentOdkLink\Jobs\UpdateXlsformTitleInFile;
use Stats4sd\FilamentOdkLink\Services\OdkLinkService;

class XlsformsRelationManager extends \Stats4sd\FilamentOdkLink\Filament\Resources\TeamResource\RelationManagers\XlsformsRelationManager
{



    public function table(Table $table): Table
    {
        return parent::table($table)
            ->actions([
                Action::make('download xls file')
                ->action(function(Xlsform $record) {

                    return Excel::download((new XlsformWorkbookExport($record)), 'xlsform.xlsx');

                })
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
