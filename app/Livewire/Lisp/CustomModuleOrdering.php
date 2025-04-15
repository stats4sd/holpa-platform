<?php

namespace App\Livewire\Lisp;

use App\Models\Holpa\LocalIndicator;
use App\Models\Team;
use App\Services\HelperService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Xlsform;

class CustomModuleOrdering extends Component
{
    /** @var Collection<Xlsform> */
    public Collection $xlsforms;

    /** @var Collection<LocalIndicator> */
    public Collection $localIndicators;

    public Team $team;

    public function mount(): void
    {
        $this->team = HelperService::getCurrentOwner();

        $this->setupLists();
    }

    public function updateOrder(array $order, Xlsform $xlsform)
    {

        ray($order);
        ray($xlsform->id);

        $orderWithKeys = collect($order)->mapWithKeys(fn ($item, $key) => [$item => ['order' => $key]]);
        $xlsform->xlsformModuleVersions()->sync($orderWithKeys);
        $this->setupLists();

    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('livewire.lisp.custom-module-ordering');
    }

    private function setupLists(): void
    {
        $this->xlsforms = Xlsform::query()
            ->with(['xlsformModuleVersions.surveyRows', 'xlsformModuleVersions.xlsformModule'])
            ->get();

        $this->localIndicators = LocalIndicator::where('global_indicator_id', null)
            ->whereDoesntHave('xlsformModuleVersion', function ($query) {
                $query->whereHas('xlsforms', function ($query) {
                    $query->whereIn('xlsforms.id', $this->xlsforms->pluck('id')->toArray());
                });
            })
            ->with('xlsformModuleVersion.surveyRows')
            ->get();
    }
}
