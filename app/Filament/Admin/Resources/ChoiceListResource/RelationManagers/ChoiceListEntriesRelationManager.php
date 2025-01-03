<?php

namespace App\Filament\Admin\Resources\ChoiceListResource\RelationManagers;

use App\Models\LanguageStringType;
use App\Models\Xlsforms\ChoiceList;
use App\Models\Xlsforms\ChoiceListEntry;
use App\Models\Xlsforms\XlsformTemplateLanguage;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

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

                $propFields = collect($choiceList->properties['extra_properties'])
                    ->map(fn($property) => Forms\Components\TextInput::make('properties.' . $property['name'])
                        ->label($property['label'])
                        ->helperText($property['helper_text'])
                    );

                $xlsformTemplateLanguages = $choiceList->template->xlsformTemplateLanguages;

                return [
                    TextInput::make('name')->required(),
                    Repeater::make('languageStrings')
                        ->label('Add Labels for the following languages:')
                        ->relationship('languageStrings')
                        ->minItems(fn() => $xlsformTemplateLanguages->count())
                        ->maxItems(fn() => $xlsformTemplateLanguages->count())
                        ->formatStateUsing(function (?ChoiceListEntry $record, $state) use ($choiceList, $xlsformTemplateLanguages) {
                            if ($record) {
                                return $state;
                            }

                            return $xlsformTemplateLanguages->map(fn(XlsformTemplateLanguage $xlsformTemplateLanguage) => [
                                'language_string_type_id' => LanguageStringType::where('name', 'label')->first()->id,
                                'xlsform_template_language_id' => $xlsformTemplateLanguage->id,
                                'text' => '',
                            ])->toArray();
                        })
                        ->schema([
                            Hidden::make('xlsform_template_language_id'),
                            Hidden::make('language_string_type_id'),
                            TextInput::make('text')
                                ->label(function (Get $get) {
                                    $xlsformTemplateLanguage = XlsformTemplateLanguage::find($get('xlsform_template_language_id'));

                                    return 'Label::' . $xlsformTemplateLanguage?->locale_language_label;
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
