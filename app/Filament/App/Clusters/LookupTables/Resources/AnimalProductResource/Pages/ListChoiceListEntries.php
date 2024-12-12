<?php

namespace App\Filament\App\Clusters\LookupTables\Resources\AnimalProductResource\Pages;

use App\Filament\App\Clusters\Actions\CreateLookupListEntryAction;
use App\Filament\App\Clusters\LookupTables\Resources\ChoiceListEntryResource;
use App\Models\LookupTables\AnimalProduct;
use App\Models\XlsformTemplates\ChoiceList;
use App\Services\HelperService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Hoa\Compiler\Llk\Rule\Choice;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

class ListChoiceListEntries extends ListRecords
{
    protected static string $resource = ChoiceListEntryResource::class;

    protected ?string $heading = 'Contextualise Survey';

    #[Url]
    public string $choiceListName = '';

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
            ->label('Add ' . $this->choiceListName),

//            Action::make('Mark as Complete')
//            ->requiresConfirmation()
//            ->action(fn () => HelperService::getSelectedTeam()?->markLookupListAsComplete(AnimalProduct::getLinkedDataset()))
//            ->visible(fn () => ! HelperService::getSelectedTeam()?->hasCompletedLookupList(AnimalProduct::getLinkedDataset())),
//
//            Action::make('Mark as Incomplete')
//            ->requiresConfirmation()
//            ->action(fn () => HelperService::getSelectedTeam()?->markLookupListAsIncomplete(AnimalProduct::getLinkedDataset()))
//            ->visible(fn () => HelperService::getSelectedTeam()?->hasCompletedLookupList(AnimalProduct::getLinkedDataset())),

        ];
    }
}
