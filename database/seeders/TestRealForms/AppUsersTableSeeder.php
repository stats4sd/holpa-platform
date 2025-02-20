<?php

namespace Database\Seeders\TestRealForms;

use Illuminate\Database\Seeder;

class AppUsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('app_users')->delete();

        \DB::table('app_users')->insert(array (
            0 =>
            array (
                'id' => 1925,
                'odk_project_id' => 1654,
                'display_name' => 'All HOLPA Data Platform LOCAL- HOLPA Data Platform LOCAL Plat 1',
                'type' => 'field_key',
                'token' => 'hGdlJ6ys3ggKLE6Lz4RCOJZVrgoY$2pbykzFJvSiLDrrFhP$g9OaRA9YIKKla0v$',
                'can_access_all_forms' => 1,
                'created_at' => '2025-02-20 15:39:42',
                'updated_at' => '2025-02-20 15:39:42',
            ),
            1 =>
            array (
                'id' => 1926,
                'odk_project_id' => 1655,
                'display_name' => 'All HOLPA Data Platform LOCAL- P1 Test Team 1',
                'type' => 'field_key',
                'token' => 'UtCI4RXAMzOB95zcgYbQ$PA20VItILcdZ497vL!FO2XLBag6e6WpJf7cOroPrIqq',
                'can_access_all_forms' => 1,
                'created_at' => '2025-02-20 15:39:44',
                'updated_at' => '2025-02-20 15:39:44',
            ),
            2 =>
            array (
                'id' => 1927,
                'odk_project_id' => 1656,
                'display_name' => 'All HOLPA Data Platform LOCAL- P1 Test Team 2 1',
                'type' => 'field_key',
                'token' => 'yabrsTfyiE84RFcaiZVzOoqPj1khRYgQhkP6Gxz3iAPSx!BhRXYEQNuTcSJPBfRL',
                'can_access_all_forms' => 1,
                'created_at' => '2025-02-20 15:39:45',
                'updated_at' => '2025-02-20 15:39:45',
            ),
            3 =>
            array (
                'id' => 1928,
                'odk_project_id' => 1657,
                'display_name' => 'All HOLPA Data Platform LOCAL- Non Program Test Team 1',
                'type' => 'field_key',
                'token' => 'Dj2n7YB$TnP95K8A87yCu2Kng3mnT0DBvppfBjWlDeKqJAGdwkG4zJwhwPSrX3!T',
                'can_access_all_forms' => 1,
                'created_at' => '2025-02-20 15:39:45',
                'updated_at' => '2025-02-20 15:39:45',
            ),
        ));


    }
}
