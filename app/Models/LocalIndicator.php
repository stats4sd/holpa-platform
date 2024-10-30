<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LocalIndicator extends Model
{
    protected $table = 'local_indicators';

    protected $guarded = ['id'];

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function globalIndicator(): BelongsTo
    {
        return $this->belongsTo(GlobalIndicator::class);
    }
}
