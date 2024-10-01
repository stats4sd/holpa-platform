<?php

namespace App\Models\TeamManagement;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleInvite extends Model
{
    protected $table = 'role_invites';

    protected $fillable = [
        'email',
        'inviter_id',
        'role_id',
        'token',
    ];

    protected $casts = [
        'is_confirmed' => 'boolean',
    ];

    // protected static function booted(): void
    // {
    //     static::addGlobalScope('unconfirmed', static function (Builder $builder) {
    //         $builder->where('is_confirmed', false);
    //     });
    // }

    // *********** RELATIONSHIPS ************ //
    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function confirm(): bool
    {
        $this->is_confirmed = 1;
        $this->save();

        return $this->is_confirmed;
    }
}
