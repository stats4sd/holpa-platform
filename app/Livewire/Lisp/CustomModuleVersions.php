<?php

namespace App\Livewire\Lisp;

use App\Exports\LocalIndicatorXlsformQuestions\CustomIndicatorExport;
use App\Models\Holpa\LocalIndicator;
use App\Models\Team;
use App\Services\HelperService;
use Exception;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Application;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Stats4sd\FilamentOdkLink\Listeners\HandleXlsformTemplateAdded;

class CustomModuleVersions extends Component implements HasForms
{
    use InteractsWithForms;

    public Team $team;

    public ?array $data;

    /** @var Collection<LocalIndicator> */
    public Collection $unmatchedLocalIndicators;

    public function mount(): void
    {
        $this->team = HelperService::getCurrentOwner();
        $this->unmatchedLocalIndicators = $this->team->localIndicators()->where('global_indicator_id', null)->get();

        $this->form->fill($this->team->toArray());
    }

    public function downloadTemplate()
    {
        return Excel::download(new CustomIndicatorExport($this->team), 'Custom Questions Template.xlsx');
    }

    public function form(Form $form): Form
    {
        return $form
            ->model($this->team)
            ->statePath('data')
            ->schema([
                Fieldset::make('Import Questions')
                    ->columns(6)
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('custom_questions_file')
                            ->columnSpan(5)
                            ->collection('custom_questions')
                            ->downloadable()
                            ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel']) // Accept only Excel files
                            ->maxSize(10240)
                            ->preserveFilenames()
                            ->helperText(fn(self $livewire) => new HtmlString('<span class="text-red-700">' . collect($livewire->getErrorBag()->get('local_indicator_list'))->join('<br/>') . '</span>')),
                        Actions::make([
                            Action::make('save_file')
                                ->label('Save File')
                                ->extraAttributes(['class' => 'buttona'])
                                ->action(fn(Get $get) => $this->uploadFile($get('custom_questions_file'))),
                        ]),
                    ]),
            ]);
    }

    public function uploadFile($customQuestionList)
    {
        // Check that a file is uploaded
        if (empty($customQuestionList) || ! is_array($customQuestionList)) {
            $this->addError('local_indicator_list', 'Please upload a file before proceeding.');

            return;
        }

        $file = reset($customQuestionList);

        // follow process for importing XlsformTemplates
        $handler = new HandleXlsformTemplateAdded;

        $moduleVersions = $this->unmatchedLocalIndicators->map(fn(LocalIndicator $localIndicator) => $localIndicator->xlsformModuleVersion);

        try {
            // $handler->processXlsformTemplate($file->getRealPath(), $moduleVersions, 'indicator');

            foreach ($moduleVersions as $moduleVersion) {
                $handler->processXlsformTemplate($file->getRealPath(), $moduleVersion, 'indicator');
            }
        } catch (Exception $e) {
            $this->addError('local_indicator_list', 'An error occurred while uploading the file.');
        }
    }

    public function render(): Factory|Application|View|\Illuminate\View\View|null
    {
        return view('livewire.lisp.custom-module-versions');
    }
}
