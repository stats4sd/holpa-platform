<?php

namespace App\Models;

use Stats4sd\FilamentOdkLink\Models\OdkLink\Interfaces\WithOdkCentralAccount;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Traits\HasOdkCentralAccount;
use Stats4sd\FilamentTeamManagement\Models\User as FilamentTeamManagementUser;

class User extends FilamentTeamManagementUser implements WithOdkCentralAccount {
    use HasOdkCentralAccount;

}
