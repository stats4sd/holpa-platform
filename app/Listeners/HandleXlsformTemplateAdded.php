<?php

namespace App\Listeners;

use App\Imports\XlsformTemplate\XlsformTemplateChoiceListImport;
use App\Imports\XlsformTemplate\XlsformTemplateWorkbookImport;
use App\Imports\XlsformTemplate\XlsformTemplateLanguageStringImport;
use App\Jobs\FinishChoiceListEntryImport;
use App\Jobs\FinishLanguageStringImport;
use App\Jobs\FinishSurveyRowImport;
use App\Jobs\ImportAllLanguageStrings;
use App\Jobs\MarkTemplateLanguagesAsNeedingUpdate;
use App\Models\ChoiceListEntry;
use App\Models\LanguageString;
use App\Models\SurveyRow;
use App\Services\XlsformTranslationHelper;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAddedEvent;

class HandleXlsformTemplateAdded
{
    public function handle(MediaHasBeenAddedEvent $event): void
    {
        Log::info('MediaHasBeenAdded event fired!');
        $model = $event->media->model;

        if ($model instanceof \App\Models\XlsformTemplate) {
            $filePath = $event->media->getPath();

            if (!$filePath) {
                Log::error('No file path found for media in collection "xlsform_file" for model ID: ' . $model->id);
                return;
            }
            $this->processXlsformTemplate($filePath, $model);

        }
    }

    public function processXlsformTemplate(string $filePath, \App\Models\XlsformTemplate $model): void
    {
        // Get the translatable headings from the XLSform workbook;
        $translatableHeadings = (new XlsformTranslationHelper())->getTreanslatableColumnsFromFile($filePath);

        // Make sure the XLSform template has the correct languages set (map over ['sheet' => 'headings'])
        $importedTemplateLanguages = $translatableHeadings->map(fn($headings) => $model->setXlsformTemplateLanguages($headings))
        ->flatten()
        ->unique();

        // make sure all the choice_lists are imported;
        (new XlsformTemplateChoiceListImport($model))->queue($filePath);

        // TODO: add validation check to make sure all names are unique in Survey + choices sheet...

        // setup the set of LanguageString imports


        // Import the XLSform workbook to survey rows and choice list entries;
        (new XlsformTemplateWorkbookImport($model, $translatableHeadings))->queue($filePath)
            ->chain([
                new FinishSurveyRowImport($model),
                new FinishChoiceListEntryImport($model),
                new ImportAllLanguageStrings($filePath, $model, $translatableHeadings, $importedTemplateLanguages),
            ]);
    }
}
