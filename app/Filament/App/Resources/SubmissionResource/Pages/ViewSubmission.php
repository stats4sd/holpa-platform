<?php

namespace App\Filament\App\Resources\SubmissionResource\Pages;

use App\Filament\App\Resources\SubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;
use Stats4sd\FilamentOdkLink\Models\OdkLink\SurveyRow;

class ViewSubmission extends ViewRecord
{
    protected static string $resource = SubmissionResource::class;
    protected static string $view = 'filament.app.resources.submission-resource.pages.view-submission';

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
            ->orderBy('row_number')
            ->get();

        // TODO: FIX RECURSION.
        $this->surveyRowData = $this->surveyRows->map(function (SurveyRow $surveyRow) {

            $value = $this->matchSubmissionContentToSurveyRows($this->record->content, $surveyRow);
            while (is_array($value)) {
                $value = $this->matchSubmissionContentToSurveyRows($value, $surveyRow);
            }

            return [
                'name' => $surveyRow->name,
                'type' => $surveyRow->type,
                'value' => $value,
            ];

        });


    }

    // Returns a collection when finding a repeat group. Otherwise returns the string value from the content
    public function matchSubmissionContentToSurveyRows(array $content, SurveyRow $surveyRow): array|string|null
    {
        ray('missioning for ' . $surveyRow->name);

        $path = explode('/', $surveyRow->path);
        array_shift($path);

        $repeatPath = $surveyRow->repeat_group_path;

        if($surveyRow->type === 'begin repeat' || $surveyRow->type === 'begin_repeat') {

            $repeatPath = explode('/', $repeatPath);
            array_shift($repeatPath);

            foreach($repeatPath as $key) {
                if($key) {
                    $content = $content[$key] ?? null;
                }
            }

            return $content;
        }


        // for normal items;
        $value = $content;

        foreach ($path as $key) {
            if ($key) {
                $value = $value[$key] ?? null;
            }
        }

        return $value;
    }

}
