<?php

namespace Database\Seeders\Test;

use App\Models\Holpa\GlobalIndicator;
use App\Models\Holpa\LocalIndicator;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Stats4sd\FilamentTeamManagement\Models\Program;

class TestSeeder extends Seeder
{
    public function run(): void
    {
        // create programs
        $program = Program::create([
            'name' => 'Test Program',
        ]);

        $teamP1 = Team::create([
            'name' => 'P1 Test Team',
        ]);
        $teamP2 = Team::create([
            'name' => 'P1 Test Team 2',
        ]);

        $program->teams()->sync([$teamP1->id, $teamP2->id]);

        $nonProgramTeam = Team::create([
            'name' => 'Non Program Test Team',
        ]);

        // create users
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $admin = User::create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $programAdmin = User::create([
            'name' => 'Test Program Admin',
            'email' => 'program_admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // assign role to users
        $admin->assignRole('Super Admin');
        $programAdmin->assignRole('Program Admin');

        // assign user to teams
        $user->teams()->attach($nonProgramTeam->id);
        $programAdmin->programs()->attach($program->id);

        // create global indicators
        GlobalIndicator::factory()->count(50)->create();

        // create local indicators
        $teams = Team::all();
        foreach ($teams as $team) {
            LocalIndicator::factory()->count(9)->create([
                'team_id' => $team->id,
            ]);
        }
    }
}
