<?php

namespace App\Models;

use App\Models\StudyCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use App\Mail\TeamManagement\InviteMember;
use App\Models\TeamManagement\TeamInvite;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Team extends Model
{
    protected $table = 'teams';

    protected $guarded = ['id'];

    // ****** TEAM MANAGEMENT STUFF ******

    /**
     * Generate an invitation to join this team for each of the provided email addresses
     */
    // public function sendInvites(array $emails): void
    // {
    //     foreach ($emails as $email) {
    //         // if email is empty, skip to next email
    //         if ($email == null || $email == '') {
    //             continue;
    //         }

    //         $invite = $this->invites()->create([
    //             'email' => $email,
    //             'inviter_id' => auth()->id(),
    //             'token' => Str::random(24),
    //         ]);

    //         Mail::to($invite->email)->send(new InviteMember($invite));

    //         // show notification after sending invitation email to user
    //         Notification::make()
    //             ->success()
    //             ->title('Invitation Sent')
    //             ->body('An email invitation has been successfully sent to ' . $email)
    //             ->send();
    //     }
    // }

    // public function invites(): HasMany
    // {
    //     return $this->hasMany(TeamInvite::class);
    // }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_members')
            ->withPivot('is_admin');
    }

    public function admins(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_members')
            ->withPivot('is_admin')
            ->wherePivot('is_admin', 1);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_members')
            ->withPivot('is_admin')
            ->wherePivot('is_admin', 0);
    }
}
