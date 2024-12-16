<?php

namespace App\Filament\App\Clusters\Localisations\Resources;

use App\Filament\App\Clusters\Localisations;
use App\Filament\App\Clusters\Localisations\Resources\ChoiceListEntryResource\Pages\ListChoiceListEntries;
use App\Models\LanguageStringType;
use App\Models\Locale;
use App\Models\Team;
use App\Models\XlsformTemplateLanguage;
use App\Models\XlsformTemplates\ChoiceList;
use App\Models\XlsformTemplates\ChoiceListEntry;
use App\Models\XlsformTemplates\LanguageString;
use App\Services\HelperService;
use Faker\Extension\Helper;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ChoiceListEntryResource extends Resource
{


    protected static ?string $model = ChoiceListEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?int $navigationSort = 2;

    protected static ?string $cluster = Localisations::class;

    protected static bool $isScopedToTenant = false; // use custom query instead to get both global entries and team-entries.

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            // only global and team-owned items
            ->where(fn(Builder $query) => $query
                ->whereHasMorph('owner', [Team::class], fn(Builder $query) => $query
                    ->where('teams.id', HelperService::getSelectedTeam()?->id
                    ))
                ->orWhere('owner_id', null)
            )
            // only localisable lists
            ->whereHas('choiceList', fn(Builder $query) => $query
                ->where('is_localisable', true)
            )
            // and only entries that are not linked to lists that are datasets
            ->whereDoesntHave('choiceList', fn(Builder $query) => $query
                ->where('is_dataset', true)
            );

    }


    public static function getNavigationItems(): array
    {
        /** @var Collection<ChoiceList> $lists */
        $lists = ChoiceList::where('is_localisable', true)
            ->where('is_dataset', false)
            ->get();

        if(!$lists) {
            return [

            ];
        }

        return $lists
            ->map(fn(ChoiceList $choiceList) => NavigationItem::make($choiceList->list_name)
                ->group('Choice Lists')
                ->icon(fn() => HelperService::getSelectedTeam()?->hasCompletedLookupList($choiceList) ? 'heroicon-o-check' : 'heroicon-o-exclamation-circle')
                ->activeIcon(fn() => HelperService::getSelectedTeam()?->hasCompletedLookupList($choiceList) ? 'heroicon-o-check' : 'heroicon-o-exclamation-circle')
                ->isActiveWhen(fn() => request()->routeIs(static::getRouteBaseName() . '.*')
                    && request()->get('choiceListName') === $choiceList->list_name
                )
                ->badge(static::getNavigationBadge(), color: static::getNavigationBadgeColor())
                ->badgeTooltip(static::getNavigationBadgeTooltip())
                ->sort(static::getNavigationSort())
                ->url(static::getNavigationUrl() . '?' . http_build_query(['choiceListName' => $choiceList->list_name]))
            )->toArray();
    }

    public static function getFormSchema(ChoiceList $choiceList): array
    {

        $propFields = collect($choiceList->properties['extra_properties'])
            ->map(fn($property) => TextInput::make('properties.' . $property['name'])
                ->label($property['label'])
                ->helperText($property['helper_text'])
            );

        return [
            Hidden::make('owner_id')
                ->default(fn() => HelperService::getSelectedTeam()?->id),
            Hidden::make('owner_type')
                ->default('App\Models\Team'),
            Hidden::make('choice_list_id')
                ->formatStateUsing(fn(?ChoiceListEntry $record, ListChoiceListEntries $livewire) => $record ? $record->choiceList->id : ChoiceList::firstWhere('list_name', $livewire->choiceListName)->id),
            TextInput::make('name')->required(),
            Repeater::make('languageStrings')
                ->label('Add Labels for the following languages:')
                ->relationship('languageStrings')
                ->minItems(fn() => HelperService::getSelectedTeam()?->locales->count())
                ->maxItems(fn() => HelperService::getSelectedTeam()?->locales->count())
                ->formatStateUsing(function (?ChoiceListEntry $record, $state, ListChoiceListEntries $livewire) {
                    if ($record) {
                        return $state;
                    }

                    $locales = HelperService::getSelectedTeam()?->locales;

                    $choiceList = ChoiceList::where('list_name', $livewire->choiceListName)->firstOrFail();
                    $xlsformTemplateLanguages = $choiceList->xlsformTemplate->xlsformTemplateLanguages;

                    return $locales->map(fn(Locale $locale) => [
                        'language_string_type_id' => LanguageStringType::where('name', 'label')->firstOrFail()->id,
                        'xlsform_template_language_id' => $xlsformTemplateLanguages->where('language_id', $locale->language_id)->firstOrFail()->id,
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


    }

    public static function table(Table $table): Table
    {
        // languages??
        $locales = HelperService::getSelectedTeam()?->locales;

        $labelColumns = $locales->map(function (Locale $locale) {
            return TextColumn::make('label_' . $locale->language->id)
                ->label('label::' . $locale->language_label)
                ->state(function (ChoiceListEntry $record) use ($locale) {


                    return $record->languageStrings
                        // only labels
                        ->filter(fn(LanguageString $languageString) => $languageString->language_string_type_id === LanguageStringType::where('name', 'label')->firstOrFail()->id)
                        // only ones for the current locale
                        ->where('xlsform_template_language_id', XlsformTemplateLanguage::where('language_id', $locale->language_id)->firstOrFail()->id)
                        ->first()
                        ?->text ?? '';
                });
        });

        return $table
            ->columns([
                TextColumn::make('choiceList.list_name')->label('list_name'),
                TextColumn::make('name')->label('name'),
                ...$labelColumns->toArray(),
                IconColumn::make('is_customised_entry')
                    ->label('Localised Entry')
                    ->boolean(),
            ])
            ->recordClasses(fn(ChoiceListEntry $record) => $record->teamRemoved->contains(HelperService::getSelectedTeam()) ? 'opacity-50' : '');
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ChoiceListEntryResource\Pages\ListChoiceListEntries::route('/'),
        ];
    }
}
