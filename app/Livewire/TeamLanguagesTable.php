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
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

// TODO - update after data structure
class TeamLanguagesTable extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public Team $team;

    /** @var Collection<Language> $languages */
    public Collection $languages;

    protected $listeners = ['refreshTable' => '$refresh'];

    public function mount()
    {
        $this->team = auth()->user()->latestTeam;
        $this->languages = $this->team->languages;
    }


    public function table(Table $table): Table
    {
        return $table
            ->relationship(fn(): BelongsToMany => $this->team->languages())
            ->inverseRelationship('teams')
            ->columns([
                Split::make([
                    TextColumn::make('language_label')->label('Language'),
                    TextColumn::make('locale')->label('Selected Translation'),
                ]),
                Panel::make([
                    Stack::make([
                        TextColumn::make('test')
                        ->formatStateUsing(fn() => 'Something goes here; probably a list of the available locales or the form. I wonder if I can put a form inside a table column. Probably...'),
                    ]),
                ])->collapsible(),

            ])
            ->actions([
                Action::make('Select Translation')->visible(fn(Language $record) => $record->pivot->locale === null)
                ->action(fn(Table $table) => ray($table->getActions())),
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
