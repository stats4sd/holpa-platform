<?php

namespace App\Livewire;

use App\Models\Holpa\LocalIndicator;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Livewire\Component;
use Stats4sd\FilamentOdkLink\Services\HelperService;

class LocalIndicators extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    /** @var Collection<LocalIndicator>  */
    public Collection $indicators;

    public ?string $selectedIndicatorId = null;

    protected $listeners = ['refreshLocalIndicators'];

    public function mount(): void
    {
        $this->getLocalIndicators();
    }

    public function getLocalIndicators(): void
    {
        $this->indicators = HelperService::getCurrentOwner()->localIndicators;
    }

    public function selectIndicator($indicatorId): void
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
            ],
            ]);
    }

    public function resetAction(): Action
    {
        return Action::make('reset')
            ->requiresConfirmation()
            ->modalHeading('Confirm Reset')
            ->modalDescription('Are you sure you want to reset all matches? This action cannot be undone.')
            ->modalSubmitActionLabel('Yes, reset')
            ->color('green')
            ->extraAttributes(['class' => 'py-2 px-6 hover-effect'])
            ->action(fn () => $this->resetIndicators());
    }

    public function resetIndicators(): void
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

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\View\View|null
    {
        return view('livewire.local-indicators', [
            'indicators' => $this->indicators,
        ]);
    }
}
