<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Tables\Table;
use App\Models\LocalIndicator;
use App\Models\GlobalIndicator;
use Filament\Tables\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class GlobalIndicators extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $selectedDomain = null;
    public $selectedIndicator = null;

    protected $listeners = ['indicatorSelected', 'resetGlobalIndicators'];

    public function indicatorSelected($data)
    {
        $this->selectedDomain = $data['indicator']['domain'];
        $this->selectedIndicator = LocalIndicator::find($data['indicator']['id']);
        $this->resetTable();
    }

    public function resetGlobalIndicators()
    {
        $this->reset('selectedDomain');
        $this->reset('selectedIndicator');
        $this->resetTable();
    }

    public function table(Table $table): Table
    {
        // When no local indicator selected show no global indicators
        if (!$this->selectedDomain) {
            return $table
                ->query(GlobalIndicator::query()->where('id', '=', 0))
                ->emptyStateHeading('Select a local indicator to see corresponding global indicators with the same theme');
        }

        // When a local indicator is selected show filtered global indicators based on theme domain
        $query = GlobalIndicator::query();

        if ($this->selectedDomain) {
            $query->whereHas('theme', function ($query) {
                $query->where('domain_id', $this->selectedDomain);
            });
        }

        return $table
            ->query($query)
            ->defaultGroup('type')
            ->columns([
                TextColumn::make('name')
                    ->label('')
            ])
            ->actions([
                Action::make('select_match')
                    ->button()
                    ->color('green')
                    ->disabled(function ($record) {
                        return $this->selectedIndicator && $this->selectedIndicator->globalIndicator
                            && $this->selectedIndicator->globalIndicator->id !== $record->id;
                    })
                    ->hidden(function ($record) {
                        if (!$this->selectedIndicator) {
                            return true;
                        }

                        $isAlreadyMatched = $record->localIndicators()
                        ->where('team_id', $this->selectedIndicator->team_id)
                        ->where('id', '!=', $this->selectedIndicator->id)
                        ->exists();

                    return $isAlreadyMatched || (
                        $this->selectedIndicator->globalIndicator &&
                        $this->selectedIndicator->globalIndicator->id === $record->id
                    );
                    })
                    ->action(function ($record) {
                        if ($this->selectedIndicator) {
                            $this->selectedIndicator->globalIndicator()->associate($record);
                            $this->selectedIndicator->save();

                            $this->dispatch('refreshLocalIndicators');

                        Notification::make()
                            ->title('Success')
                            ->body($this->selectedIndicator->name . ' has been mathced with ' . $this->selectedIndicator->globalIndicator->name .  '!')
                            ->success()
                            ->send();
                        }
                    }),

                Action::make('remove_match')
                    ->button()
                    ->color('blue')
                    ->hidden(function ($record) {
                        return !$this->selectedIndicator || !$this->selectedIndicator->globalIndicator
                            || $this->selectedIndicator->globalIndicator->id !== $record->id;
                    })
                    ->action(function ($record) {
                        if ($this->selectedIndicator) {
                            $this->selectedIndicator->globalIndicator()->dissociate();
                            $this->selectedIndicator->save();

                            $this->dispatch('refreshLocalIndicators');

                            Notification::make()
                                ->title('Success')
                                ->body('Match removed!')
                                ->success()
                                ->send();
                        }
                    }),

                    Action::make('already_matched')
                        ->label('Already Matched')
                        ->color('secondary')
                        ->hidden(function ($record) {
                            if (!$this->selectedIndicator) {
                                return true;
                            }

                            $isAlreadyMatched = $record->localIndicators()
                            ->where('team_id', $this->selectedIndicator->team_id)
                            ->where('id', '!=', $this->selectedIndicator->id)
                            ->exists();
                        return !$isAlreadyMatched;
                        })
            ])
            ->paginated(false);
    }

    public function render()
    {
        return view('livewire.global-indicators');
    }
}
