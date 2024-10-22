<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Stats4sd\FilamentTeamManagement\Models\User as FilamentTeamManagementUser;

class User extends FilamentTeamManagementUser
{
    // Issue: use HasRoles trait in main repo User model, but admin panel > User resource > list view > table column "Roles" still not showing roles...
    use HasRoles;
}
