<?php

namespace Database\Seeders\Test;

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        $program = Program::create([
            'name' => 'Test Program',
        ]);

        $program->teams()->createMany([
            ['name' => 'Test Team 1'],
            ['name' => 'Test Team 2'],
        ]);

    }
}
