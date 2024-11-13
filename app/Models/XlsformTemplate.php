<?php

namespace App\Models;

use App\Models\SurveyRow;
use App\Models\XlsformTemplateLanguage;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformTemplate as OdkLinkXlsformTemplate;

class XlsformTemplate extends OdkLinkXlsformTemplate
{

    public function xlsformTemplateLanguages(): HasMany
    {
        return $this->hasMany(XlsformTemplateLanguage::class);
    }

    public function surveyRows(): HasMany
    {
        return $this->hasMany(SurveyRow::class);
    }

    public function choiceLists(): HasMany
    {
        return $this->hasMany(ChoiceList::class);
    }

}
