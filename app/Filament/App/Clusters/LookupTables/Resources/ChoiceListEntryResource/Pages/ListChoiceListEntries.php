<?php

namespace App\Filament\App\Clusters\LookupTables\Resources\ChoiceListEntryResource\Pages;

use App\Filament\App\Clusters\LookupTables\Resources\ChoiceListEntryResource;
use App\Models\LookupTables\AnimalProduct;
use App\Models\XlsformTemplates\ChoiceList;
use App\Services\HelperService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;

class ListChoiceListEntries extends ListRecords
{
    protected static string $resource = ChoiceListEntryResource::class;

    protected ?string $heading = 'Contextualise Survey';

    #[Url]
    public string $choiceListName = '';
    public ChoiceList $choiceList;

    public function mount(): void
    {
        parent::mount();
        $this->choiceList = ChoiceList::firstWhere('list_name', $this->choiceListName);
    }

    public function table(Table $table): Table
    {
        return parent::table($table)
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->whereHas('choiceList', fn(Builder $query) => $query
                    ->where('choice_lists.list_name', $this->choiceListName)
                )
            );
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ChoiceListEntryResource\Widgets\ChoiceListEntriesInfo::make(['choiceListName' => $this->choiceListName]),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Add new ' . Str::singular($this->choiceListName)),

            Action::make('Mark as Complete')
            ->action(fn () => HelperService::getSelectedTeam()?->markLookupListAsComplete($this->choiceList))
            ->visible(fn () => ! HelperService::getSelectedTeam()?->hasCompletedLookupList($this->choiceList))
            ->after(fn() => $this->redirect($this->getResource()::getUrl('index', ['choiceListName' => $this->choiceListName]))),

            Action::make('Mark as Incomplete')
            ->action(fn () => HelperService::getSelectedTeam()?->markLookupListAsIncomplete($this->choiceList))
            ->visible(fn () => HelperService::getSelectedTeam()?->hasCompletedLookupList($this->choiceList))
            ->after(fn() => $this->redirect($this->getResource()::getUrl('index', ['choiceListName' => $this->choiceListName]))),

        ];
    }
}
