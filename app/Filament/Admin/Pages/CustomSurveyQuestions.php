<?php

namespace App\Filament\Admin\Pages;

use App\Models\Team;
use Filament\Forms\Get;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Services\HelperService;
use Awcodes\TableRepeater\Header;
use Filament\Actions\StaticAction;
use Filament\Tables\Actions\Action;
use App\Models\Holpa\LocalIndicator;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Awcodes\TableRepeater\Components\TableRepeater;
use Filament\Actions\Concerns\InteractsWithActions;
use Stats4sd\FilamentOdkLink\Models\OdkLink\SurveyRow;
use App\Filament\Shared\WithXlsformModuleVersionQuestionEditing;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformModuleVersion;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\Locale;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\LanguageStringType;

// class CustomSurveyQuestions extends Page implements HasActions, HasForms, HasTable
class CustomSurveyQuestions extends Page 
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Module Builder';

    protected static string $view = 'filament.admin.pages.custom-survey-questions';

    // use WithXlsformModuleVersionQuestionEditing;

    // use InteractsWithActions;
    // use InteractsWithForms;
    // use InteractsWithForms;
    // use InteractsWithTable;

    // public LocalIndicator $localIndicator;

    // public XlsformModuleVersion $xlsformModuleVersion;

    // public bool $expanded = true;

    // public bool $processing = false;

    // public array $data;

    // public function mount(): void
    // {
    //     $this->localIndicator = LocalIndicator::first();

    //     $this->xlsformModuleVersion = $this->localIndicator->xlsformModuleVersion ?? $this->localIndicator->xlsformModuleVersion()->create([
    //         'name' => $this->localIndicator->name,
    //         'is_default' => false,
    //     ]);

    //     $this->localIndicator->xlsformModuleVersion()->associate($this->xlsformModuleVersion);
    //     $this->localIndicator->save();

    //     $this->xlsformModuleVersion->load(['surveyRows.languageStrings', 'surveyRows.choiceList.choiceListEntries.languageStrings']);

    //     $this->form->fill($this->xlsformModuleVersion->toArray());
    // }

    // // show questions in a table for better readability
    // public function table(Table $table): Table
    // {
    //     // $locales = $this->localIndicator->team->locales;
    //     $locales = Team::find(3)->locales;

    //     return $this->customModuleQuestionTable($table, $locales, $this->xlsformModuleVersion);
    // }

}
