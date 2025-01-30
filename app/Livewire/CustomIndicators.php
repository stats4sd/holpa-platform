<?php

namespace App\Livewire;

use App\Exports\CustomIndicatorExport;
use App\Models\Holpa\LocalIndicator;
use App\Models\Team;
use Carbon\Carbon;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Stats4sd\FilamentOdkLink\Services\HelperService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CustomIndicators extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public Team $team;

    public function mount(): void
    {
        $this->team = HelperService::getCurrentOwner();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(LocalIndicator::query()
                ->where('team_id', $this->team->id))
            ->columns([
                TextColumn::make('name')->label(''),
                CheckboxColumn::make('is_custom')->label('Confirm and add'),
                SelectColumn::make('survey')
                    ->label('Select the survey')
                    ->disabled(fn ($record) => $record->is_custom !== 1)
                    ->options([
                    'household' => 'Household',
                    'fieldwork' => 'Fieldwork',
                ]),
            ])
            ->paginated(false)
            ->emptyStateHeading('No custom indicators');
    }

    private function hasCustomIndicatorsWithSurvey(string $survey): bool
    {
        return LocalIndicator::query()
            ->where('team_id', $this->team->id)
            ->where('is_custom', 1)
            ->where('survey', $survey)
            ->exists();
    }

    private function hasUnassignedCustomIndicators(): bool
    {
        return $this->team->localIndicators()->where('is_custom', 1)->whereNull('survey')->exists();
    }

    public function resetIndicators(): void
    {
        // Reset the 'is_custom' flag to 0 for all local indicators belonging to this team
        $this->team->localIndicators()->update(['is_custom' => 0]);
    }

    public function downloadHouseholdTemplate(): BinaryFileResponse
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        $filename = "HOLPA - {$this->team->name} - Custom Indicator Household Template - {$currentDate}.xlsx";
        return Excel::download(new CustomIndicatorExport($this->team, 'household'), $filename);
    }

    public function downloadFieldworkTemplate(): BinaryFileResponse
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        $filename = "HOLPA - {$this->team->name} - Custom Indicator Fieldwork Template - {$currentDate}.xlsx";
        return Excel::download(new CustomIndicatorExport($this->team, 'fieldwork'), $filename);
    }

    public function render(): \Illuminate\Contracts\View\Factory|Application|View|\Illuminate\View\View|null
    {
        return view('livewire.custom-indicators');
    }
}
