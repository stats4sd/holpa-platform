<?php

namespace App\Models;

use App\Models\LanguageString;
use App\Models\XlsformTemplate;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SurveyRow extends Model implements HasLanguageStrings
{

    protected $casts = [
        'properties' => 'collection',
        'required' => 'boolean',
    ];

    public function xlsformTemplate(): BelongsTo
    {
        return $this->belongsTo(XlsformTemplate::class);
    }

    public function languageStrings(): MorphMany
    {
        return $this->morphMany(LanguageString::class, 'linked_entry');
    }


    public function required(): Attribute
    {
        return new Attribute(
            get: fn($value) => $value,
            set: function($value) {
                return match(strtolower($value)) {
                    'true','yes' => true,
                    'false','no' => false,
                    default => false,
                };
            }
        );
    }
}
