<?php

namespace App\Livewire;

use App\Models\Holpa\LocalIndicator;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformTemplate;

class CustomModuleOrdering extends Component
{

    /** @var Collection<XlsformTemplate> */
    public Collection $xlsformTemplates;

    /** @var Collection<LocalIndicator> */
    public Collection $localIndicators;

    public function mount(): void
    {
        $this->xlsformTemplates = XlsformTemplate::where('available', true)
            ->with('xlsformModules.defaultXlsformVersion.surveyRows')
            ->get();

        $this->localIndicators = LocalIndicator::where('global_indicator_id', null)
            ->with('xlsformModuleVersion.surveyRows')
            ->get();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('livewire.custom-module-ordering');
    }
}
