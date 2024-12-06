<?php

namespace App\Livewire;

use App\Models\Team;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LocalIndicator;
use Filament\Tables\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class CustomIndicators extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(LocalIndicator::query()
                ->where('team_id', auth()->user()->latestTeam->id))
            ->columns([
                TextColumn::make('name')->label(''),
                CheckboxColumn::make('is_custom')->label('Confirm and add'),
            ])
            ->paginated(false)
            ->emptyStateHeading('No custom indicators');;
    }

    public function resetIndicators()
    {
        $team = Team::find(auth()->user()->latestTeam->id);
        // Reset the 'is_custom' flag to 0 for all local indicators belonging to this team
        $team->localIndicators()->update(['is_custom' => 0]);
    }

    public function render()
    {
        return view('livewire.custom-indicators');
    }
}
