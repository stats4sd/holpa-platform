<?php

namespace App\Filament\App\Resources\SubmissionResource\Pages;

use App\Filament\App\Resources\SubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Stats4sd\FilamentOdkLink\Models\OdkLink\SurveyRow;

class ViewSubmission extends ViewRecord
{
    protected static string $resource = SubmissionResource::class;
    protected static string $view = 'filament.app.resources.submission-resource.pages.view-submission';
    protected ?string $maxContentWidth = '7xl';


    /** @var Collection<SurveyRow> */
    public Collection $surveyRows;

    /** @var Collection<Collection> */
    public Collection $surveyRowData;

    public function getHeading(): string
    {
        return 'Submission: ' . $this->record->odk_id;
    }

    public function getSubheading(): string|Htmlable|null
    {
        return $this->record->xlsformVersion->xlsform->title . ' - Raw Data';
    }


    public function mount(int|string $record): void
    {
        parent::mount($record);


        $this->surveyRows = $this->record
            ->xlsformVersion
            ->xlsform
            ->surveyRows()
            ->with('defaultLabel')
            ->orderBy('row_number')
            ->get();

        $this->surveyRowData = collect($this->record->content)
            ->map(function ($value, $key) {
                if (!$value) {
                    return null;
                }

                return $this->matchContentToSurveyRow($value, $key);
            })
            ->filter(fn($value) => ! isset($value['type']) || $value['type'] !== 'note')
       ;

    }

    public function matchContentToSurveyRow(string|array|null $value, string $key): ?Collection
    {

       // ray()->count();

        /** @var SurveyRow $surveyRow */
        $surveyRow = $this->surveyRows->where('name', $key)->first();

        if (!$surveyRow) {
            // TODO: need to keep survey rows from older versions in the future!
            // Meanwhile, try to guess the type:

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
            foreach ($value as $repeatInstance) {

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


        // if $key is a navigationLink to a repeat section...
        if (Str::endsWith($key, '@odata.navigationLink')) {
            return collect();
        }

        return collect([
            'name' => $surveyRow->name,
            'type' => $surveyRow->type,
            'label' => $surveyRow->defaultLabel?->text,
            'value' => $value,
        ]);

    }

}
