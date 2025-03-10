<?php

namespace App\Livewire;

use App\Exports\CustomIndicatorExport;
use App\Models\Holpa\LocalIndicator;
use App\Models\Team;
use Carbon\Carbon;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Application;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\HelperService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CustomModuleVersions extends Component implements HasForms
{
    use InteractsWithForms;

    public Team $team;

    /** @var Collection<LocalIndicator> */
    public Collection $unmatchedLocalIndicators;

    public function mount(): void
    {
        $this->team = HelperService::getCurrentOwner();
        $this->unmatchedLocalIndicators = $this->team->localIndicators()->where('global_indicator_id', null)->get();

    }

    public function render(): Factory|Application|View|\Illuminate\View\View|null
    {
        return view('livewire.custom-module-versions');
    }
}
