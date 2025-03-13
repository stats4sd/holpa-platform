<?php

namespace App\Livewire;

use App\Models\Team;
use Filament\Forms\Get;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Livewire\Attributes\On;
use Filament\Actions\StaticAction;
use Illuminate\Support\Collection;
use Filament\Tables\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Hidden;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Actions\Contracts\HasActions;
use App\Imports\XlsformTemplateLanguageImport;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Xlsform;
use Stats4sd\FilamentOdkLink\Models\OdkLink\SurveyRow;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Stats4sd\FilamentOdkLink\Models\OdkLink\ChoiceListEntry;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformTemplate;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\Locale;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\Language;
use Stats4sd\FilamentOdkLink\Exports\XlsformTemplateTranslationsExport;

class TeamTranslationEntry extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public ?array $data = [];
    public Locale $locale;

    public Team $team;
    public Language $language;
    public ?Locale $selectedLocale = null;

    public bool $expanded;

    public function mount(Locale $locale): void
    {
        $this->selectedLocale = Locale::find($this->language->pivot->locale_id);

        // Reference: https://filamentphp.com/docs/3.x/forms/adding-a-form-to-a-livewire-component#adding-the-form

        $this->form->fill();
        // $this->form->fill($locale->toArray());
    }


    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\View\View|null
    {
        return view('livewire.team-translation-entry');
    }

    public function table(Table $table): Table
    {
        return $table
            ->relationship(
                fn() => $this->language
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
                        $record->language->owners()->updateExistingPivot($this->team->id, ['locale_id' => $record->id]);
                        $this->selectedLocale = $record;
                    }),

                // add Edit button to edit translation label
                // TODO: change Edit button to a pencil icon, move it to the left of translation label in the table
                Action::make('Edit')
                    ->label('Edit')
                    ->disabled(fn(Locale $record) => $record->is_default == 1)
                    ->modalHeading(fn(Locale $record) => 'Edit Translation Label for ' . $record->language_label)
                    ->modalContent(fn(Locale $record) => view('livewire.team-translation-label', ['locale' => $record, 'team' => $this->team]))
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false),

                Action::make('view-edit')
                    ->label('View / Edit Translation')
                    ->modalHeading(fn(Locale $record) => 'View / Edit Translation for ' . $record->language_label)
                    ->modalContent(fn(Locale $record) => view('team-translation-review', ['locale' => $record, 'team' => $this->team]))
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false),

            ]);
    }

    #[On('closeModal')]
    public function closeModal() {}

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

    // this form will be showed in a popup modal when user click "Edit" button
    // form data will be stored in "data" array
    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                // Questions:
                // 1. How do we know which locale is being edited by user?
                // 2. Can we pass locale id into the form?

                TextInput::make('description')
                    ->label('Enter a label for the translation')
                    ->helperText('E.g. "Portuguese (Brazil)"'),

                // TODO: add locale id into form
                TextInput::make('id'),
            ])
            ->statePath('data');
    }

    // execute when user click Submit button in popup modal
    public function submit(): void
    {
        ray('TeamTranslationEntry.submit()...');

        // TODO:
        // 1. get locale record id
        // 2. get translation label entered by user
        // 3. update locale record
        // 4. table will refresh to show latest translation label automatically

        ray($this->form->getState());

        // get submitted form data
        $formData = $this->form->getState();

        // hardcode temporary for testing
        $localeId = 4;

        $newDescription = $formData['description'];

        Locale::find($localeId)
            ->update(['description' => $newDescription]);
    }

    // execute when user click Cancel button in popup modal
    public function cancel(): void
    {
        ray('TeamTranslationEntry.cancel()...');

        // do nothing
    }
}
