<?php

namespace Database\Seeders\Test;

use App\Models\Program;
use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        Team::create([
            'name' => 'Test Team Without Program',
        ]);

    }
}
