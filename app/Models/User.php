<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Traits\HasRoles;
use App\Mail\TeamManagement\InviteUser;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Notifications\Notifiable;
use App\Models\TeamManagement\RoleInvite;
use Filament\Models\Contracts\HasTenants;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasDefaultTenant;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Althinect\FilamentSpatieRolesPermissions\Concerns\HasSuperAdmin;

class User extends Authenticatable implements FilamentUser, HasTenants, HasDefaultTenant
{
    use HasFactory, Notifiable;
    use HasRoles;


    /**
     * The attributes that a
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ****** TEAM MANAGEMENT STUFF ******

    /**
     * Generate an invitation to be a role for each of the provided email addresses
     */
    public function sendInvites(array $items): void
    {
        foreach ($items as $item) {
            // if email is empty, skip to next email
            if ($item['email'] == null || $item['email'] == '') {
                continue;
            }

            $invite = $this->invites()->create([
                'email' => $item['email'],
                'role_id' => $item['role'],
                'token' => Str::random(24),
            ]);

            Mail::to($invite->email)->send(new InviteUser($invite));

            // show notification after sending invitation email to user
            Notification::make()
                ->success()
                ->title('Invitation Sent')
                ->body('An email invitation has been successfully sent to ' . $item['email'])
                ->send();
        }
    }

    public function invites(): HasMany
    {
        return $this->hasMany(RoleInvite::class, 'inviter_id');
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_members')->withPivot('is_admin');
    }

    public function programs(): BelongsToMany
    {
        return $this->belongsToMany(Program::class);
    }

    public function belongsToTeam(Team $team): bool
    {
        return $this->teams->contains($team);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('Super Admin');
    }

    // ****** FILAMENT PANEL STUFF ******

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    // ****** MULTI-TENANCY STUFF ******

    // Admin users can access all teams
    public function canAccessTenant(Model $tenant): bool
    {
        // check permission
        if ($this->can('view all teams')) {
            return true;
        }

        // check if user belong to this team
        if ($this->teams->contains($tenant)) {
            return true;
        }

        // check if this team belong to any program belong to user
        foreach ($this->programs as $program) {
            if ($program->teams->contains($tenant)) {
                return true;
            }
        }

        // user cannot access this team
        return false;
    }

    public function getTenants(Panel $panel): array|Collection
    {
        if ($this->can('view all teams')) {
            return Team::all();
        } else {
            // find all teams belong to all programs of user
            $allTeamsIdInPrograms = array();

            foreach ($this->programs as $program) {
                $allPrograms = $program->teams->pluck('id');
                array_push($allTeamsIdInPrograms, $allPrograms);
            }

            // flatten array
            $allTeamsIdInPrograms = Arr::flatten($allTeamsIdInPrograms);

            // find all teams belong to user
            $allTeamIds = $this->teams->pluck('id');

            // all accessible teams = all teams belong to all programs of user + all teams belong to user
            $allAccessibleTeamIds = array();
            array_push($allAccessibleTeamIds, Arr::flatten($allTeamsIdInPrograms), Arr::flatten($allTeamIds));

            // find all accessible Team models
            $allAccessibleTeams = Team::whereIn('id', Arr::flatten($allAccessibleTeamIds))->get();

            return $allAccessibleTeams;
        }
    }

    // The last team the user was on.
    public function latestTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'latest_team_id');
    }

    public function getDefaultTenant(Panel $panel): ?Model
    {
        return $this->latestTeam;
    }
}
