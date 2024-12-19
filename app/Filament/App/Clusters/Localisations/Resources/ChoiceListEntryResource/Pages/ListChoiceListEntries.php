<?php

namespace App\Filament\App\Clusters\Localisations\Resources\ChoiceListEntryResource\Pages;

use App\Filament\App\Clusters\Localisations\Resources\ChoiceListEntryResource;
use App\Models\XlsformTemplates\ChoiceList;
use App\Models\XlsformTemplates\ChoiceListEntry;
use App\Services\HelperService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Form;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
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

        if(!$this->choiceListName) {
            $this->choiceList = ChoiceList::where('is_localisable', true)
            ->where('has_custom_handling', false)
            ->first();

            $this->choiceListName = $this->choiceList->list_name;
        } else {
            $this->choiceList = ChoiceList::firstWhere('list_name', $this->choiceListName);
        }
    }

    public function table(Table $table): Table
    {
        return parent::table($table)
            ->modifyQueryUsing(fn(Builder $query) => $query
                ->whereHas('choiceList', fn(Builder $query) => $query
                    ->where('choice_lists.list_name', $this->choiceListName)
                )
            )
            ->actions([
                EditAction::make()->visible(fn(ChoiceListEntry $record) => !$record->is_global_entry)
                ->form(fn(Form $form) => $form->schema(fn() => $this->getResource()::getFormSchema($this->choiceList))),
                DeleteAction::make()->visible(fn(ChoiceListEntry $record) => !$record->is_global_entry),
                \Filament\Tables\Actions\Action::make('Toggle Removed')
                    ->label(fn(ChoiceListEntry $record) => $record->teamRemoved->contains(HelperService::getSelectedTeam()) ? 'Restore to Context' : 'Remove from Context')
                    ->visible(fn(ChoiceListEntry $record) => $record->is_global_entry)
                    ->action(fn(ChoiceListEntry $record) => $record->toggleRemoved(HelperService::getSelectedTeam())),
            ]);
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
                ->label('Add new ' . Str::singular($this->choiceListName))
                ->form(fn(Form $form) => $form
                    ->schema(fn() => $this->getResource()::getFormSchema($this->choiceList))),

            Action::make('Mark as Complete')
                ->action(fn() => HelperService::getSelectedTeam()?->markLookupListAsComplete($this->choiceList))
                ->visible(fn() => !HelperService::getSelectedTeam()?->hasCompletedLookupList($this->choiceList))
                ->after(fn() => $this->redirect($this->getResource()::getUrl('index', ['choiceListName' => $this->choiceListName]))),

            Action::make('Mark as Incomplete')
                ->action(fn() => HelperService::getSelectedTeam()?->markLookupListAsIncomplete($this->choiceList))
                ->visible(fn() => HelperService::getSelectedTeam()?->hasCompletedLookupList($this->choiceList))
                ->after(fn() => $this->redirect($this->getResource()::getUrl('index', ['choiceListName' => $this->choiceListName]))),

        ];
    }
}
