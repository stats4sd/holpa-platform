<?php

namespace App\Filament\App\Resources\SubmissionResource\Pages;

use App\Filament\App\Resources\SubmissionResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\StaticAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Submission;
use Stats4sd\FilamentOdkLink\Models\OdkLink\SurveyRow;
use Stats4sd\FilamentOdkLink\Services\OdkLinkService;

class ViewSubmission extends ViewRecord
{
    protected static string $resource = SubmissionResource::class;
    protected static string $view = 'filament.app.resources.submission-resource.pages.view-submission';
    protected ?string $maxContentWidth = '7xl';


    /** @var Collection<SurveyRow> */
    public Collection $surveyRows;

    /** @var Collection<Collection> */
    public Collection $surveyRowData;

    /** @return Submission | Model */
    public function getRecord(): Submission|Model
    {
        /** @var Submission $record */
        $record = parent::getRecord();
        return $record;
    }


    public function getHeading(): string
    {
        return 'Submission: ' . $this->record->odk_id;
    }

    public function getSubheading(): string|Htmlable|null
    {
        return $this->record->xlsform_title . ' - Raw Data';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('edit_on_central')
                ->openUrlInNewTab(true)
                ->label('Edit Submission')
                ->modalWidth('2xl')
                ->modalDescription(fn(): HtmlString => new HtmlString('

                Editing the raw submission is only recommended when you have clear information directly from the enumerator that some specific values are incorrect. In most cases, it is recommended to modify the processed data instead.

                Do you wish to continue?
                '))
                ->modalSubmitAction(false)
                ->extraModalFooterActions([
                    Action::make('edit_on_central_continue')
                        ->openUrlInNewTab(true)
                        ->label('Edit on ODK Central')
                        ->modalDescription(fn(): HtmlString => new HtmlString('
                    This action will take you directly to the ODK Central platform that this xlsform is deployed to. You will be asked to log in with your existing HOLPA Platform email and password.
                       <br/><br/>
                     Once you have completed your edits, it is recommended to close the tab and return to this page.
                    '))
                            ->modalSubmitAction(false)
                            ->extraModalFooterActions([
                            Action::make('edit_confirm')
                                ->label('Edit on ODK Central')
                                ->action(function () {

                                    $odkLinkService = app()->make(OdkLinkService::class);

                                    $token = $odkLinkService->authenticate();

                                    $cookies = Http::get(config('filament-odk-link.odk.base_endpoint') . '/sessions', [
                                        'email' => config('filament-odk-link.odk.username'),
                                        'password' => config('filament-odk-link.odk.password'),
                                    ])->cookies();

                                    foreach ($cookies->toArray() as $cookie) {
                                        setcookie($cookie);
                                    }

                                    return redirect($this->record->odk_central_view_page_url);

                                }),
                        ]),
                ]),

        ];
    }


    public function mount(int|string $record): void
    {
        parent::mount($record);


        $this->surveyRows = $this->record
            ->xlsformVersion
            ->xlsform
            ->surveyRows()
            ->with(['defaultLabel', 'choiceList.choiceListEntries'])
            ->orderBy('row_number')
            ->get();

        $this->surveyRowData = collect($this->record->content)
            ->map(function ($value, $key) {
                if (!$value) {
                    return null;
                }

                return $this->matchContentToSurveyRow($value, $key);
            })
            ->filter(fn($value) => !isset($value['type']) || $value['type'] !== 'note');

    }

    public function matchContentToSurveyRow(string|array|null $value, string $key): ?Collection
    {

        // ray()->count();

        /** @var SurveyRow $surveyRow */
        $surveyRow = $this->surveyRows->where('name', $key)->first();

        if (!$surveyRow) {
            // TODO: need to keep survey rows from older versions in the future!
            // Meanwhile, try to guess the type:

            // if $key is a navigationLink to a repeat section...
            if (
                Str::contains($key, '@odata.navigationLink') ||
                $key === '__id'
            ) {
                return collect();
            }


            if (is_array($value)) {
                return collect($value)
                    ->map(fn($innerValue, $innerKey) => $this->matchContentToSurveyRow($innerValue, $innerKey));
            }

            return collect([
                'name' => $key,
                'label' => $key,
                'type' => null,
                'value' => $value,
            ]);
        }


        // for repeat groups
        if ($surveyRow->type === 'begin repeat' || $surveyRow->type === 'begin_repeat' ||
            $surveyRow->type === 'end repeat' || $surveyRow->type === 'end_repeat'
        ) {

            $output = collect([]);
            foreach ($value as $index => $repeatInstance) {
                $output[] = collect([
                    'key' => $key,
                    'iteration' => $index + 1,
                    'count' => collect($value)->count(),
                    'name' => $surveyRow->name,
                    'type' => $surveyRow->type,
                    'label' => $surveyRow->defaultLabel?->text,
                ]);
                $output[] = collect($repeatInstance)
                    ->map(fn($innerValue, $innerKey) => $this->matchContentToSurveyRow($innerValue, $innerKey));
            }

            return $output;
        }

        if ($surveyRow->type === 'geopoint') {
            return collect();
        }

        // for regular groups
        if (is_array($value)) {

            return collect($value)
                ->map(fn($innerValue, $innerKey) => $this->matchContentToSurveyRow($innerValue, $innerKey));
        }

        // for selects, find the value label
        if (Str::startsWith($surveyRow->type, 'select_')) {

            if (!$surveyRow->choice_list_id) {
                ray($surveyRow);
            }

            $choiceListEntries = $surveyRow->choiceList->choiceListEntries;
            $entry = $choiceListEntries->where('name', $value)->first();

            if (!$entry?->defaultLabel?->text) {
                ray($surveyRow);
            }

            $value = $entry?->defaultLabel?->text ?? $value;


        }

        return collect([
            'name' => $surveyRow->name,
            'type' => $surveyRow->type,
            'label' => $surveyRow->defaultLabel?->text,
            'value' => $value,
        ]);

    }

}
