<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LocalIndicator extends Model
{
    use HasFactory;
    
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

    public function getDomainAttribute()
    {
        return $this->theme->domain;
    }
}
