<?php

namespace App\Livewire\DataCollection;

use App\Models\SampleFrame\LocationLevel;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Url;
use Livewire\Component;
use Filament\Tables\Table;

class DataCollectionByLocation extends Component implements HasTable, HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    #[Reactive]
    public int $locationLevelId;


    #[Computed]
    public function locationLevel()
    {
        return LocationLevel::find($this->locationLevelId);
    }


    public function table(Table $table): Table
    {
        return $table
            ->relationship(fn() => $this->locationLevel->locations())
            ->heading(fn() => Str::plural($this->locationLevel->name))
            ->columns([
                TextColumn::make('name')->label('Location'),
                TextColumn::make('farms_all_count')->label('Total Farms'),
                TextColumn::make('farms_tes')->label('No. Farms with Household Survey'),
                TextColumn::make('farms_tes2')->label('No. Farms with Fieldwork Survey'),
            ]);
    }


    public function render()
    {
        return view('livewire.data-collection.data-collection-by-location');
    }
}
