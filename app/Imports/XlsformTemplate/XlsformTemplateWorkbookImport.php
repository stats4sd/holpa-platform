<?php

namespace App\Imports\XlsformTemplate;

use App\Jobs\FinishImport;
use App\Models\ChoiceList;
use App\Models\ChoiceListEntry;
use App\Models\SurveyRow;
use App\Models\XlsformTemplate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\AfterImport;

class XlsformTemplateWorkbookImport implements WithMultipleSheets, ShouldQueue, WithChunkReading, WithEvents
{

    use RegistersEventListeners;
    use Importable;

    public function __construct(public XlsformTemplate $xlsformTemplate, public Collection $translatableHeadings)
    {
    }

    // Specify the "survey" sheet
    public function sheets(): array
    {
        return [
            'survey' => new XlsformTemplateSurveyImport($this->xlsformTemplate, $this->translatableHeadings['survey']),
            'choices' => new XlsformTemplateChoicesImport($this->xlsformTemplate, $this->translatableHeadings['choices']),
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function afterImport(AfterImport $event): void
    {
        // find all Survey Rows linked to the XlsformTemplate that were not updated during the import... and delete them.
        $surveyRowsToDelete = $this->xlsformTemplate
            ->surveyRows()
            ->select(['id', 'updated_during_import'])
            ->get()
            ->filter(fn(SurveyRow $surveyRow) => $surveyRow->updated_during_import === false);

        // we need to actually get the models instead of deleting them with a query, because we need to trigger the deleting event.
        SurveyRow::destroy($surveyRowsToDelete->pluck('id'));

        // we also need to delete the choiceLists that were not updated during the import.
        $choicesToDelete = $this->xlsformTemplate
            ->choiceListEntries()
            ->select(['choice_list_entries.id', 'updated_during_import'])
            ->get()
            ->filter(fn(ChoiceListEntry $choiceListEntry) => $choiceListEntry->updated_during_import === false);

        ChoiceListEntry::destroy($choicesToDelete->pluck('id'));

        ChoiceList::has('choiceListEntries', '=', 0)->delete();
    }
}
