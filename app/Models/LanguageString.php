<?php

namespace App\Models;

use App\Models\Language;
use App\Models\SurveyRow;
use App\Models\LanguageStringType;
use App\Models\XlsformTemplateLanguage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LanguageString extends Model
{
    use HasFactory;

    public function surveyRow(): BelongsTo
    {
        return $this->belongsTo(SurveyRow::class);
    }

    public function languageStringType(): BelongsTo
    {
        return $this->belongsTo(LanguageStringType::class);
    }

    public function xlsformTemplateLanguage(): BelongsTo
    {
        return $this->belongsTo(XlsformTemplateLanguage::class);
    }
}
