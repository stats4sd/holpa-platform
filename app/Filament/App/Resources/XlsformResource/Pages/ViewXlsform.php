<?php

namespace App\Filament\App\Resources\XlsformResource\Pages;

use App\Filament\Actions\ExportDataAction;
use App\Filament\App\Resources\XlsformResource;
use App\Http\Controllers\SurveyMonitoringController;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Xlsform;
use Stats4sd\FilamentOdkLink\Services\OdkLinkService;

class ViewXlsform extends ViewRecord
{
    protected static string $resource = XlsformResource::class;

    protected static string $view = 'filament.app.resources.xlsform-resource.pages.view-xlsform';

    /** @phpstan-return Xlsform */
    public function getRecord(): Model|Xlsform
    {
        /** @var Xlsform $record */
        $record = parent::getRecord();

        return $record;
    }

    public function getHeading(): string|Htmlable
    {
        return 'Survey Monitoring';
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }

    public function getContentTabLabel(): ?string
    {
        return 'Summary';
    }

    // Question: This function is being called twice now...
    // Is it possible to define a public variable to store the summary array?
    public function getSummary(): array
    {
        return (new SurveyMonitoringController)->getSubmissionSummary($this->record->owner, false);
    }

    /**
     * @throws BindingResolutionException
     */
    public function exportAsExcelFile()
    {
        return app()->make(OdkLinkService::class)->exportAsExcelFile($this->getRecord());
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download raw survey data')
                ->label('Download Raw Survey Data')
                ->url(url('/resources/xlsform-resource/'.$this->record->id.'/download-data-direct-from-odk')),
            ExportDataAction::Make('download-processed')
                ->label('Export Processed Data'),
        ];
    }
}
