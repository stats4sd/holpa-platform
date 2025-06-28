<?php

namespace App\Filament\App\Pages\SurveyLocations;

use App\Filament\Shared\WithXlsformModuleVersionQuestionEditing;
use App\Models\Team;
use App\Services\HelperService;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\EditAction;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Stats4sd\FilamentOdkLink\Models\OdkLink\SurveyRow;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\LanguageStringType;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\Locale;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformModuleVersion;

class ContextQuestions extends Page implements HasActions, HasForms, HasTable
{

    use WithXlsformModuleVersionQuestionEditing;

    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.survey-locations.context-questions';

    public Team $team;
    public XlsformModuleVersion $xlsformModuleVersion;

    public function mount(): void
    {
        $team = HelperService::getCurrentOwner();

        if ($team === null) {
            abort(404);
        }

        $this->team = $team;

        $this->xlsformModuleVersion = $this->team->localContextModuleVersion->load(['surveyRows.languageStrings', 'surveyRows.choiceList.choiceListEntries.languageStrings']);

        $this->form->fill($this->xlsformModuleVersion->toArray());
    }

    protected static bool $shouldRegisterNavigation = false;

    public function table(Table $table): Table
    {
        $locales = $this->team->locales;
        return $this->customModuleQuestionTable($table, $locales, $this->xlsformModuleVersion);
    }


}
