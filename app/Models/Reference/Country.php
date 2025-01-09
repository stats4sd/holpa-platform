<?php

namespace App\Models\Reference;

use App\Models\Team;
use Illuminate\Database\Eloquent\Model;
use Znck\Eloquent\Traits\BelongsToThrough;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Country extends Model
{
    use BelongsToThrough;

    protected $table = 'countries';

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function continent(): \Znck\Eloquent\Relations\BelongsToThrough
    {
        return $this->belongsToThrough(Continent::class, Region::class);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function xlsformModuleVersion(): HasMany
    {
        return $this->hasMany(XlsformModuleVersion::class);
    }
}
