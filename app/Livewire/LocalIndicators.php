<?php

namespace App\Livewire;

use App\Models\Holpa\LocalIndicator;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Livewire\Component;

class LocalIndicators extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public $indicators;
    public $selectedIndicatorId = null;

    protected $listeners = ['refreshLocalIndicators'];

    public function mount()
    {
        $this->indicators = LocalIndicator::where('team_id', auth()->user()->latestTeam->id)->get();
    }

    public function refreshLocalIndicators()
    {
        $this->indicators = LocalIndicator::where('team_id', auth()->user()->latestTeam->id)->get();
    }

    public function selectIndicator($indicatorId)
    {
        $this->selectedIndicatorId = $indicatorId;
        $indicator = LocalIndicator::find($indicatorId);
        $this->dispatch('indicatorSelected', [
            'indicator' => [
                'id' => $indicator->id,
                'name' => $indicator->name,
                'domain' => $indicator->domain,
                'global_indicator_id' => $indicator->global_indicator_id,
                'team_id' => $indicator->team_id,
            ]
            ]);
    }

    public function resetAction(): Action
    {
        return Action::make('reset')
            ->requiresConfirmation()
            ->modalHeading('Confirm Reset')
            ->modalSubheading('Are you sure you want to reset all matches? This action cannot be undone.')
            ->modalButton('Yes, reset')
            ->color('green')
            ->extraAttributes(['class' => 'py-2 px-6 hover-effect'])
            ->action(fn () => $this->resetIndicators());
    }

    public function resetIndicators()
    {
        foreach ($this->indicators as $indicator) {
            $indicator->globalIndicator()->dissociate();
            $indicator->save();
        }

        $this->selectedIndicatorId = null;

        $this->dispatch('resetGlobalIndicators');

        Notification::make()
            ->title('Success')
            ->body('All matches have been removed!')
            ->success()
            ->send();
    }

    public function render()
    {
        return view('livewire.local-indicators', [
            'indicators' => $this->indicators,
        ]);
    }
}
