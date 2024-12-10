<?php

namespace App\Models\XlsformTemplates;

use App\Models\LanguageStringType;
use App\Models\XlsformTemplateLanguage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Znck\Eloquent\Relations\BelongsToThrough;

class LanguageString extends Model
{

    protected $casts = [
        'updated_during_import' => 'boolean',
    ];

    // A language string is linked to either a SurveyRow or a ChoiceListEntry;
    public function linkedEntry(): MorphTo
    {
        return $this->morphTo('linked_entry');
    }

    public function languageStringType(): BelongsTo
    {
        return $this->belongsTo(LanguageStringType::class);
    }

    public function xlsformTemplateLanguage(): BelongsTo
    {
        return $this->belongsTo(XlsformTemplateLanguage::class);
    }

    public function xlsformTemplate(): BelongsToThrough
    {
        return $this->belongsToThrough(XlsformTemplate::class, XlsformTemplateLanguage::class);
    }
}
