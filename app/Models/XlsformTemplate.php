<?php

namespace App\Models;

use App\Models\Language;
use App\Models\SurveyRow;
use App\Models\XlsformTemplateLanguage;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformTemplate as OdkLinkXlsformTemplate;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class XlsformTemplate extends OdkLinkXlsformTemplate
{
    use HasFactory;

    public function xlsformTemplatelanguages(): HasMany
    {
        return $this->hasMany(XlsformTemplateLanguage::class);
    }

    public function surveyRows(): HasMany
    {
        return $this->hasMany(SurveyRow::class);
    }

}
