<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\XlsformTemplateResource\Pages;
use App\Filament\Admin\Resources\XlsformTemplateResource\RelationManagers;
use App\Models\Xlsforms\XlsformTemplate;
use Stats4sd\FilamentOdkLink\Filament\Resources\XlsformTemplateResource as OdkLinkXlsformTemplateResource;

class XlsformTemplateResource extends OdkLinkXlsformTemplateResource
{
    protected static ?string $model = XlsformTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationGroup = 'Survey and Datasets';

    public static function getRelations(): array
    {
        return [
            RelationManagers\XlsformModuleRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListXlsformTemplates::route('/'),
            'create' => Pages\CreateXlsformTemplate::route('/create'),
            'edit' => Pages\EditXlsformTemplate::route('/{record}/edit'),
            'view' => Pages\ViewXlsformTemplate::route('/{record}'),
        ];
    }
}
