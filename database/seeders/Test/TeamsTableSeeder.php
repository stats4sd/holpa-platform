<?php

namespace Database\Seeders\Test;

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

        DB::table('teams')->insert(array(
            0 =>
            array(
                'id' => 1,
                'name' => 'Test User Team',
                'website' => NULL,
                'description' => 'Important Note: This team is created by seeder file. It does not have ODK project ID. Please create a new team for xlsform related testing',
                'created_at' => '2024-08-15 10:22:09',
                'updated_at' => '2024-08-15 10:22:09',
            ),
        ));
    }
}
