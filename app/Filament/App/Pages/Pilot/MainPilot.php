<?php

namespace App\Filament\App\Pages\Pilot;

use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Livewire\Attributes\Url;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Xlsform;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Services\HelperService;
use Stats4sd\FilamentOdkLink\Services\OdkLinkService;

class MainPilot extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.app.pages.pilot.main-pilot';

    protected ?string $heading = 'Survey Testing - Pilot and Enumerator Training';

    protected ?string $subheading = 'Test with enumerators; pilot with real farmers';

    #[Url]
    public string $tab = 'xlsforms';

    public function getBreadcrumbs(): array
    {
        return [
            SurveyDashboard::getUrl() => 'Survey Dashboard',
            PilotIndex::getUrl() => 'Pilot',
            static::getUrl() => static::getTitle(),
        ];
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getRecord(): Team
    {
        return HelperService::getCurrentOwner();
    }


}
