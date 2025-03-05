<?php

namespace Database\Seeders\TestRealForms;

use Illuminate\Database\Seeder;

class OdkProjectsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('odk_projects')->delete();

        \DB::table('odk_projects')->insert(array (
            0 =>
            array (
                'id' => 1654,
                'owner_type' => 'Stats4sd\\FilamentOdkLink\\Models\\OdkLink\\Platform',
                'owner_id' => 1,
                'name' => 'HOLPA Data Platform LOCAL- HOLPA Data Platform LOCAL Plat',
                'description' => NULL,
                'archived' => NULL,
                'created_at' => '2025-02-20 15:39:42',
                'updated_at' => '2025-02-20 15:39:42',
            ),
            1 =>
            array (
                'id' => 1655,
                'owner_type' => 'App\\Models\\Team',
                'owner_id' => 1,
                'name' => 'HOLPA Data Platform LOCAL- P1 Test Team',
                'description' => NULL,
                'archived' => NULL,
                'created_at' => '2025-02-20 15:39:44',
                'updated_at' => '2025-02-20 15:39:44',
            ),
            2 =>
            array (
                'id' => 1656,
                'owner_type' => 'App\\Models\\Team',
                'owner_id' => 2,
                'name' => 'HOLPA Data Platform LOCAL- P1 Test Team 2',
                'description' => NULL,
                'archived' => NULL,
                'created_at' => '2025-02-20 15:39:45',
                'updated_at' => '2025-02-20 15:39:45',
            ),
            3 =>
            array (
                'id' => 1657,
                'owner_type' => 'App\\Models\\Team',
                'owner_id' => 3,
                'name' => 'HOLPA Data Platform LOCAL- Non Program Test Team',
                'description' => NULL,
                'archived' => NULL,
                'created_at' => '2025-02-20 15:39:45',
                'updated_at' => '2025-02-20 15:39:45',
            ),
        ));


    }
}
