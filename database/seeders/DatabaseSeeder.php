<?php

namespace Database\Seeders;

use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\LanguageStringTypeSeeder;
use Stats4sd\FilamentOdkLink\Database\Seeders\PlatformSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PlatformSeeder::class);

        $this->call([
            Test\ProgramsTableSeeder::class,
            Test\TeamsTableSeeder::class,
            Test\ProgramTeamTableSeeder::class,

            Test\TestUserSeeder::class,
            Test\ModelHasRolesTableSeeder::class,
            Test\RoleHasPermissionsTableSeeder::class,

            Test\ProgramUserTableSeeder::class,
            Test\TeamMembersTableSeeder::class,
            LanguageSeeder::class,
            LanguageStringTypeSeeder::class,
        ]);

        $this->call(ThemesTableSeeder::class);
    }
}
