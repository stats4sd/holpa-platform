<?php

namespace App\Models\Xlsforms;

use App\Models\HasLanguageStrings;
use App\Models\XlsformTemplates\LanguageString;
use App\Models\XlsformTemplates\SurveyRow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;

class FormSurveyRow extends Model implements HasLanguageStrings
{
    use CascadesDeletes;

    protected array $cascadesDeletes = ['languageStrings'];

    protected $casts = [
        'properties' => 'collection',
        'required' => 'boolean',
        'updated_during_import' => 'boolean',
    ];

    public function xlsform(): BelongsTo
    {
        return $this->belongsTo(Xlsform::class);
    }

    // for custom survey rows that replace existing survey rows, we link to the replaced survey row
    public function replaces(): BelongsTo
    {
        return $this->belongsTo(SurveyRow::class, 'replaces_id');
    }


//    // for custom survey rows that are inserted after an existing survey row, we link to the existing survey row
//    public function after(): BelongsTo
//    {
//        return $this->belongsTo(SurveyRow::class, 'after_id');
//    }


    public function languageStrings(): MorphMany
    {
        return $this->morphMany(LanguageString::class, 'linked_entry');
    }

}
