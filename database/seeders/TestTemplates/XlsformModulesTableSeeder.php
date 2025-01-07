<?php

namespace Database\Seeders\TestTemplates;

use Illuminate\Database\Seeder;

class XlsformModulesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('xlsform_modules')->delete();

        \DB::table('xlsform_modules')->insert(array (
            0 =>
            array (
                'id' => 1,
                'form_type' => 'App\\Models\\Xlsforms\\XlsformTemplate',
                'form_id' => 1,
                'label' => 'metadata',
                'name' => 'metadata',
                'created_at' => '2025-01-07 16:20:02',
                'updated_at' => '2025-01-07 16:20:02',
            ),
            1 =>
            array (
                'id' => 2,
                'form_type' => 'App\\Models\\Xlsforms\\XlsformTemplate',
                'form_id' => 1,
                'label' => 'context',
                'name' => 'context',
                'created_at' => '2025-01-07 16:20:02',
                'updated_at' => '2025-01-07 16:20:02',
            ),
            2 =>
            array (
                'id' => 3,
                'form_type' => 'App\\Models\\Xlsforms\\XlsformTemplate',
                'form_id' => 1,
                'label' => 'coffee',
                'name' => 'coffee',
                'created_at' => '2025-01-07 16:20:02',
                'updated_at' => '2025-01-07 16:20:02',
            ),
            3 =>
            array (
                'id' => 4,
                'form_type' => 'App\\Models\\Xlsforms\\XlsformTemplate',
                'form_id' => 1,
                'label' => 'drinks',
                'name' => 'drinks',
                'created_at' => '2025-01-07 16:20:02',
                'updated_at' => '2025-01-07 16:20:02',
            ),
            4 =>
            array (
                'id' => 5,
                'form_type' => 'App\\Models\\Xlsforms\\XlsformTemplate',
                'form_id' => 1,
                'label' => 'end',
                'name' => 'end',
                'created_at' => '2025-01-07 16:20:02',
                'updated_at' => '2025-01-07 16:20:02',
            ),
            5 =>
            array (
                'id' => 6,
                'form_type' => 'App\\Models\\Xlsforms\\XlsformTemplate',
                'form_id' => 2,
                'label' => 'metadata',
                'name' => 'metadata',
                'created_at' => '2025-01-07 16:20:52',
                'updated_at' => '2025-01-07 16:20:52',
            ),
            6 =>
            array (
                'id' => 7,
                'form_type' => 'App\\Models\\Xlsforms\\XlsformTemplate',
                'form_id' => 2,
                'label' => 'context',
                'name' => 'context',
                'created_at' => '2025-01-07 16:20:52',
                'updated_at' => '2025-01-07 16:20:52',
            ),
            7 =>
            array (
                'id' => 8,
                'form_type' => 'App\\Models\\Xlsforms\\XlsformTemplate',
                'form_id' => 2,
                'label' => 'fieldwork',
                'name' => 'fieldwork',
                'created_at' => '2025-01-07 16:20:52',
                'updated_at' => '2025-01-07 16:20:52',
            ),
            8 =>
            array (
                'id' => 9,
                'form_type' => 'App\\Models\\Xlsforms\\XlsformTemplate',
                'form_id' => 2,
                'label' => 'end',
                'name' => 'end',
                'created_at' => '2025-01-07 16:20:52',
                'updated_at' => '2025-01-07 16:20:52',
            ),
        ));


    }
}
