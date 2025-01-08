<?php

namespace App\Livewire;

use App\Exports\XlsformTemplateLanguageExport;
use App\Models\Team;
use App\Models\XlsformLanguages\Language;
use App\Models\XlsformLanguages\Locale;
use App\Models\XlsformLanguages\XlsformModuleVersionLocale;
use App\Models\Xlsforms\XlsformTemplate;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

// TODO - update after data structure
class TeamLanguagesTable extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public Team $team;

    protected $listeners = ['refreshTable' => '$refresh'];

    public function mount()
    {
        $this->team = auth()->user()->latestTeam;
    }

    public function table(Table $table): Table
    {
        return $table
            ->relationship(fn(): BelongsToMany => $this->team->languages())
            ->inverseRelationship('teams')
            ->columns([
                TextColumn::make('language_label')->label('Language'),
                TextColumn::make('locale')->label('Selected Translation'),
            ])
            ->actions([
                Action::make('Select Translation')->visible(fn(Language $record) => $record->pivot->locale === null)
                    ->form(fn(Language $record) => [
                        Select::make('locale_id')
                            ->options(Locale::where('language_id', $record->id)->get()->pluck('language_label', 'id'))
                        ->label('Select the translation to use')
                        ->helperText('You will be able to review the text before finalising your decision')

                    ]),
                Action::make('Remove Translation')->visible(fn(Language $record) => $record->pivot->locale !== null),
            ])
            ->paginated(false)
            ->emptyStateHeading('No translations selected');
    }


    public function render(): View
    {
        return view('livewire.team-languages-table');
    }
}
