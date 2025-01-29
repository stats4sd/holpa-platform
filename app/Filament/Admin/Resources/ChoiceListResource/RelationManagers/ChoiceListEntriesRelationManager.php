<?php

namespace App\Filament\Admin\Resources\ChoiceListResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Stats4sd\FilamentOdkLink\Models\OdkLink\ChoiceList;
use Stats4sd\FilamentOdkLink\Models\OdkLink\ChoiceListEntry;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\LanguageStringType;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\Locale;

class ChoiceListEntriesRelationManager extends RelationManager
{
    protected static string $relationship = 'ChoiceListEntries';

    public function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema(function (ChoiceListEntriesRelationManager $livewire) {

                /** @var ChoiceList $choiceList */
                $choiceList = $this->getOwnerRecord();

                if(isset($choiceList->properties['extra_properties'])) {
                    $propFields = collect($choiceList->properties['extra_properties'])
                        ->map(fn($property) => Forms\Components\TextInput::make('properties.' . $property['name'])
                            ->label($property['label'])
                            ->helperText($property['helper_text'])
                        );
                } else {
                    $propFields = collect([]);
                }


                /** @var Collection<Locale> $locales */
                $locales = $choiceList->xlsformModuleVersion->locales;

                return [
                    TextInput::make('name')->required(),
                    Repeater::make('languageStrings')
                        ->label('Add Labels for the following languages:')
                        ->relationship('languageStrings')
                        ->minItems(fn() => $locales->count())
                        ->maxItems(fn() => $locales->count())
                        ->formatStateUsing(function (?ChoiceListEntry $record, $state) use ($choiceList, $locales) {
                            if ($record) {
                                return $state;
                            }

                            return $locales->map(fn(Locale $locale) => [
                                'language_string_type_id' => LanguageStringType::where('name', 'label')->first()->id,
                                'locale_id' => $locale->id,
                                'text' => '',
                            ])->toArray();
                        })
                        ->schema([
                            Hidden::make('locale_id'),
                            Hidden::make('language_string_type_id'),
                            TextInput::make('text')
                                ->label(function (Get $get) use ($locales) {
                                    $locale = $locales->firstWhere('id', $get('locale_id'));
                                    return 'Label::' . $locale->language_label;
                                })
                                ->required(),
                        ])
                        ->addable(false)
                        ->deletable(false),
                    ...$propFields->toArray(),
                ];
            });
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('languageStrings.text')
                    ->separator(', '),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
