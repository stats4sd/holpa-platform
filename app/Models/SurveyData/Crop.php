<?php

namespace App\Models\SurveyData;

use App\Models\Interfaces\PerformanceRepeatModel;
use App\Models\SurveyData\FarmSurveyData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Submission;
use App\Models\Traits\HasLinkedDataset;

class Crop extends Model implements PerformanceRepeatModel
{
    use HasLinkedDataset;

    protected $table = 'crops';

    protected $casts = [
        'properties' => 'collection',
    ];

    public function farmSurveyData(): BelongsTo
    {
        return $this->belongsTo(FarmSurveyData::class, 'submission_id', 'submission_id');
    }

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class, 'submission_id', 'id');
    }
}
