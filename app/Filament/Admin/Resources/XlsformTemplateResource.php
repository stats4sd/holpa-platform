<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\XlsformTemplateResource\Pages;
use App\Filament\Admin\Resources\XlsformTemplateResource\RelationManagers;
use App\Models\XlsformTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Stats4sd\FilamentOdkLink\Filament\Resources\XlsformTemplateResource as OdkLinkXlsformTemplateResource;

class XlsformTemplateResource extends OdkLinkXlsformTemplateResource
{
    protected static ?string $model = XlsformTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getRelations(): array
    {
        return [
            RelationManagers\XlsformTemplateLanguageRelationManager::class,
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
