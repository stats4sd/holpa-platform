<?php

namespace Database\Seeders\Test;

use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('teams')->delete();

        Team::create([
            'name' => 'Test Team',
            'description' => 'This is a test team',
        ]);
    }
}
