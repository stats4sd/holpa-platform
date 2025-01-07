<?php

namespace Database\Seeders\TestTemplates;

use Illuminate\Database\Seeder;

class XlsformModuleVersionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('xlsform_module_versions')->delete();

        \DB::table('xlsform_module_versions')->insert(array (
            0 =>
            array (
                'id' => 1,
                'xlsform_module_id' => 1,
                'name' => 'Global metadata',
                'is_default' => 1,
                'created_at' => '2025-01-07 16:20:02',
                'updated_at' => '2025-01-07 16:20:02',
            ),
            1 =>
            array (
                'id' => 2,
                'xlsform_module_id' => 2,
                'name' => 'Global context',
                'is_default' => 1,
                'created_at' => '2025-01-07 16:20:02',
                'updated_at' => '2025-01-07 16:20:02',
            ),
            2 =>
            array (
                'id' => 3,
                'xlsform_module_id' => 3,
                'name' => 'Global coffee',
                'is_default' => 1,
                'created_at' => '2025-01-07 16:20:02',
                'updated_at' => '2025-01-07 16:20:02',
            ),
            3 =>
            array (
                'id' => 4,
                'xlsform_module_id' => 4,
                'name' => 'Global drinks',
                'is_default' => 1,
                'created_at' => '2025-01-07 16:20:02',
                'updated_at' => '2025-01-07 16:20:02',
            ),
            4 =>
            array (
                'id' => 5,
                'xlsform_module_id' => 5,
                'name' => 'Global end',
                'is_default' => 1,
                'created_at' => '2025-01-07 16:20:02',
                'updated_at' => '2025-01-07 16:20:02',
            ),
            5 =>
            array (
                'id' => 6,
                'xlsform_module_id' => 6,
                'name' => 'Global metadata',
                'is_default' => 1,
                'created_at' => '2025-01-07 16:20:52',
                'updated_at' => '2025-01-07 16:20:52',
            ),
            6 =>
            array (
                'id' => 7,
                'xlsform_module_id' => 7,
                'name' => 'Global context',
                'is_default' => 1,
                'created_at' => '2025-01-07 16:20:52',
                'updated_at' => '2025-01-07 16:20:52',
            ),
            7 =>
            array (
                'id' => 8,
                'xlsform_module_id' => 8,
                'name' => 'Global fieldwork',
                'is_default' => 1,
                'created_at' => '2025-01-07 16:20:52',
                'updated_at' => '2025-01-07 16:20:52',
            ),
            8 =>
            array (
                'id' => 9,
                'xlsform_module_id' => 9,
                'name' => 'Global end',
                'is_default' => 1,
                'created_at' => '2025-01-07 16:20:52',
                'updated_at' => '2025-01-07 16:20:52',
            ),
        ));


    }
}
