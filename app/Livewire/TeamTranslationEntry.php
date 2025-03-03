<?php

namespace App\Livewire;

use App\Imports\XlsformTemplateLanguageImport;
use App\Models\Team;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Livewire\Attributes\On;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Xlsform;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformTemplate;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Stats4sd\FilamentOdkLink\Exports\XlsformTemplateTranslationsExport;
use Stats4sd\FilamentOdkLink\Models\OdkLink\ChoiceListEntry;
use Stats4sd\FilamentOdkLink\Models\OdkLink\SurveyRow;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\Language;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\Locale;

class TeamTranslationEntry extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public Team $team;
    public Language $language;
    public ?Locale $selectedLocale = null;

    public bool $expanded;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\View\View|null
    {
        $this->selectedLocale = $this->team->locales()->where('language_id', $this->language->id)->first();

        return view('livewire.team-translation-entry');
    }

    public function table(Table $table): Table
    {
        return $table
            ->relationship(fn() => $this->language
                ->locales()
            )
            ->recordClasses(fn(Locale $record) => $record->id === $this->selectedLocale?->id ? 'success-row' : '')
            ->columns([
                TextColumn::make('language_label')->label('Available Translations'),
                TextColumn::make('status')->label('Status'),
            ])
            ->paginated(false)
            ->emptyStateHeading("No translations available.")
            ->heading('')
            ->headerActions([
                Action::make('Add New')
                    ->extraAttributes(['class' => 'buttonb'])
                    ->icon('heroicon-o-plus-circle')
                    ->form([
                        TextInput::make('description')->label('Enter a label for the translation')
                            ->helperText('E.g. "Portuguese (Brazil)"'),
                    ])
                    ->action(function (array $data) {
                        $this->language->locales()->create([
                            'description' => $data['description'],
                            'creator_id' => $this->team->id,
                        ]);
                    }),
            ])
            ->actions([
                Action::make('Select')
                    ->icon(fn(Locale $record) => $record->id === $this->selectedLocale?->id ? 'heroicon-o-check-circle' : '')
                    ->color(fn(Locale $record) => $record->id === $this->selectedLocale?->id ? 'success' : 'primary')
                    ->label(fn(Locale $record) => $record->id === $this->selectedLocale?->id ? 'Selected' : 'Select')
                    ->disabled(fn(Locale $record) => $this->selectedLocale?->id === $record->id)
                    ->tooltip('Select this translation for your survey')
                    ->action(function (Locale $record) {
                        $record->language->teams()->updateExistingPivot($this->team->id, ['locale_id' => $record->id]);
                    }),

                Action::make('view-edit')
                    ->label('View / Edit Translation')
                    ->modalHeading(fn(Locale $record) => 'View / Edit Translation for ' . $record->language_label)
                    ->modalContent(fn(Locale $record) => view('team-translation-review', ['locale' => $record, 'team' => $this->team]))
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false),

            ]);
    }

    #[On('closeModal')]
    public function closeModal()
    {

    }

    public function validateFileUpload(array $upload, Locale $record, XlsformTemplate $xlsformTemplate): \Closure
    {
        return function (string $attribute, string $value, \Closure $fail) use ($upload, $record, $xlsformTemplate) {


            $file = collect($upload)->first();

            /** @var Collection $rows */
            $rows = Excel::toCollection((object)[], $file)[0];

            $headers = $rows->shift();

            // check the required columns exist
            $rowTypeIndex = $headers->contains('type');
            $nameIndex = $headers->contains('name');
            $stringTypeIndex = $headers->contains('translation type');
            $currentTranslationIndex = $headers->contains($record->language_label);

            if ($currentTranslationIndex === false || $nameIndex === false || $stringTypeIndex === false) {

                $missingColumns = collect([
                    $nameIndex ? '' : 'name',
                    $stringTypeIndex ? '' : 'translation type',
                    $currentTranslationIndex ? '' : $record->language_label,
                ]);

                return $fail("The uploaded file is missing the following required columns: {$missingColumns->join(', ')}. Please check that you are using the template downloaded from this platform, and please do not edit the column headers.");
            }

            // check that all the required names are present
            $processor = new XlsformTemplateTranslationsExport($xlsformTemplate, $record);

            $templateSurveyRows = $xlsformTemplate->surveyRows
                ->map(fn(SurveyRow $entry) => $processor->processEntry($entry));
            $templateChoiceListEntries = $xlsformTemplate->choiceListEntries
                ->map(fn(ChoiceListEntry $entry) => $processor->processEntry($entry));

            // make sure all template survey rows are present in the $rows collection
            $missingSurveyRows = $templateSurveyRows
                ->filter(fn($entry) => $rows->doesntcontain($entry, '=', 'name'))
                ->filter(fn($entry) => $rows->doesntcontain($entry, '=', 'translation_type'));

            // make sure all template choice list rows are present in the $rows collection
            $missingChoiceListEntries = $templateChoiceListEntries
                ->filter(fn($entry) => $rows->doesntcontain($entry, '=', 'name'))
                ->filter(fn($entry) => $rows->doesntcontain($entry, '=', 'translation_type'));

            // TODO: finish validating that all required rows are present;
            // TODO: validate that all translation_types are valid language string types.


            return true;

        };
    }

}
