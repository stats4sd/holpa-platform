<?php

namespace App\Filament\App\Pages;

use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Page;
use Filament\Pages\Tenancy\EditTenantProfile;

class EditTeamPage extends EditTenantProfile
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    //protected static string $view = 'filament.app.pages.edit-team-page';
    public static function getLabel(): string
    {
        return 'Team Profile';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                Textarea::make('description'),
                Select::make('languages')
                    ->relationship('languages', 'name')
                    ->preload()
                    ->searchable()
                    ->multiple(),
            ]);
    }

    protected function getRedirectUrl(): ?string
    {
        return Filament::getHomeUrl();
    }
}
