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
        $this->surveyRows = $this->record->xlsformVersion->xlsform->surveyRows()->orderBy('row_number')->get();

        dd($this->record->content);


    }

}
