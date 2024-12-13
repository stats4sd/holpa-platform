<?php

namespace App\Models\SurveyData;

use App\Models\Traits\HasLinkedDataset;
use Illuminate\Database\Eloquent\Model;
use App\Models\SurveyData\FarmSurveyData;
use App\Models\Interfaces\RepeatModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Submission;

class Livestock extends Model implements RepeatModel
{
    use HasLinkedDataset;

    protected $table = 'livestocks';

    protected $casts = [
        'properties' => 'collection',
    ];

    public function livestockUses(): HasMany
    {
        return $this->hasMany(LivestockUse::class);
    }

    public function farmSurveyData(): BelongsTo
    {
        return $this->belongsTo(FarmSurveyData::class, 'submission_id', 'submission_id');
    }

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class, 'submission_id', 'id');
    }
}
