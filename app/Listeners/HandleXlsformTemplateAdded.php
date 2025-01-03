<?php

namespace App\Listeners;

use App\Imports\XlsformTemplate\XLsformModuleImport;
use App\Imports\XlsformTemplate\XlsformTemplateChoiceListImport;
use App\Imports\XlsformTemplate\XlsformTemplateWorkbookImport;
use App\Jobs\FinishChoiceListEntryImport;
use App\Jobs\FinishSurveyRowImport;
use App\Jobs\ImportAllLanguageStrings;
use App\Models\Interfaces\WithXlsformFile;
use App\Models\Xlsforms\XlsformModule;
use App\Models\Xlsforms\XlsformModuleVersion;
use App\Models\Xlsforms\XlsformTemplate;
use App\Services\XlsformTranslationHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAddedEvent;

class HandleXlsformTemplateAdded
{
    public function handle(MediaHasBeenAddedEvent $event): void
    {
        Log::info('MediaHasBeenAdded event fired!');
        $model = $event->media->model;

        if (
            $model instanceof XlsformTemplate ||
            $model instanceof XlsformModuleVersion
        ) {
            $filePath = $event->media->getPath();

            if (!$filePath) {
                Log::error('No file path found for media in collection "xlsform_file" for model ID: ' . $model->id);
                return;
            }

            // a template file will have multiple modules. Create these first so we can link Survey Rows to them during the main import
            if ($model instanceof XlsformTemplate) {
                $modules = $this->createModules($filePath, $model);
            } else {
                $modules = null;
            }

            // for a single moduleversion upload, just run the process once
            $this->processXlsformTemplate($filePath, $model, $modules);

        }
    }

    public
    function processXlsformTemplate(string $filePath, WithXlsformFile $model, ?Collection $modules = null): void
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

        // Import the XLSform workbook to survey rows and choice list entries;
        (new XlsformTemplateWorkbookImport($model, $translatableHeadings))->queue($filePath)
            ->chain([
                new FinishSurveyRowImport($model),
                new FinishChoiceListEntryImport($model),
                new ImportAllLanguageStrings($filePath, $model, $translatableHeadings, $importedTemplateLanguages),
            ]);
    }

    public function createModules(string $filePath, XlsformTemplate $model): Collection
    {
        // no queue as this is a small / quick import.
        (new XLsformModuleImport($model))->import($filePath);

        // get the 'default' version of all xlsform module versions for each module linked to the xlsform template.
        return $model->xlsformModules
            ->map(fn(XlsformModule $module) => $module
                ->xlsformModuleVersions
                ->filter(fn(XlsformModuleVersion $xlsformModuleVersion) => $xlsformModuleVersion->is_default)
            )
            ->flatten();
    }
}
