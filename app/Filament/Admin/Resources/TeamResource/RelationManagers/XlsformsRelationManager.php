<?php

namespace App\Filament\Admin\Resources\TeamResource\RelationManagers;

use App\Exports\XlsformExport\XlsformWorkbookExport;
use App\Models\Xlsform;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;

class XlsformsRelationManager extends \Stats4sd\FilamentOdkLink\Filament\Resources\TeamResource\RelationManagers\XlsformsRelationManager
{


    public function table(Table $table): Table
    {
        return parent::table($table)
            ->actions([
                Action::make('download xls file')
                ->action(fn(Xlsform $record) => Excel::download((new XlsformWorkbookExport($record)), 'xlsform.xlsx'))
            ]);
    }
}
