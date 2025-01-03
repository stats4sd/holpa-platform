<?php

namespace App\Models;

use Stats4sd\FilamentTeamManagement\Models\User as FilamentTeamManagementUser;

class User extends FilamentTeamManagementUser {

    public function canDoSomething(): bool
    {
        return true;
    }

}
