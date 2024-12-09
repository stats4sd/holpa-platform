<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LocalIndicator;
use Filament\Notifications\Notification;

class LocalIndicators extends Component
{
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
