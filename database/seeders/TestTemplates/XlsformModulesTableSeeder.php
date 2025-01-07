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
                'created_at' => '2025-01-07 16:56:14',
                'updated_at' => '2025-01-07 16:56:14',
            ),
            1 =>
            array (
                'id' => 2,
                'form_type' => 'App\\Models\\Xlsforms\\XlsformTemplate',
                'form_id' => 1,
                'label' => 'context',
                'name' => 'context',
                'created_at' => '2025-01-07 16:56:14',
                'updated_at' => '2025-01-07 16:56:14',
            ),
            2 =>
            array (
                'id' => 3,
                'form_type' => 'App\\Models\\Xlsforms\\XlsformTemplate',
                'form_id' => 1,
                'label' => 'coffee',
                'name' => 'coffee',
                'created_at' => '2025-01-07 16:56:14',
                'updated_at' => '2025-01-07 16:56:14',
            ),
            3 =>
            array (
                'id' => 4,
                'form_type' => 'App\\Models\\Xlsforms\\XlsformTemplate',
                'form_id' => 1,
                'label' => 'diet_quality',
                'name' => 'diet_quality',
                'created_at' => '2025-01-07 16:56:14',
                'updated_at' => '2025-01-07 16:56:14',
            ),
            4 =>
            array (
                'id' => 5,
                'form_type' => 'App\\Models\\Xlsforms\\XlsformTemplate',
                'form_id' => 1,
                'label' => 'end',
                'name' => 'end',
                'created_at' => '2025-01-07 16:56:14',
                'updated_at' => '2025-01-07 16:56:14',
            ),
            5 =>
            array (
                'id' => 6,
                'form_type' => 'App\\Models\\Xlsforms\\XlsformTemplate',
                'form_id' => 2,
                'label' => 'metadata_fieldwork',
                'name' => 'metadata_fieldwork',
                'created_at' => '2025-01-07 17:00:40',
                'updated_at' => '2025-01-07 17:00:40',
            ),
            6 =>
            array (
                'id' => 7,
                'form_type' => 'App\\Models\\Xlsforms\\XlsformTemplate',
                'form_id' => 2,
                'label' => 'context_fieldwork',
                'name' => 'context_fieldwork',
                'created_at' => '2025-01-07 17:00:40',
                'updated_at' => '2025-01-07 17:00:40',
            ),
            7 =>
            array (
                'id' => 8,
                'form_type' => 'App\\Models\\Xlsforms\\XlsformTemplate',
                'form_id' => 2,
                'label' => 'fieldwork',
                'name' => 'fieldwork',
                'created_at' => '2025-01-07 17:00:40',
                'updated_at' => '2025-01-07 17:00:40',
            ),
            8 =>
            array (
                'id' => 9,
                'form_type' => 'App\\Models\\Xlsforms\\XlsformTemplate',
                'form_id' => 2,
                'label' => 'end_fieldwork',
                'name' => 'end_fieldwork',
                'created_at' => '2025-01-07 17:00:40',
                'updated_at' => '2025-01-07 17:00:40',
            ),
            9 =>
            array (
                'id' => 10,
                'form_type' => 'App\\Models\\Xlsforms\\Xlsform',
                'form_id' => 8,
                'label' => 'P1 Test Team custom module',
                'name' => 'P1 Test Team custom module',
                'created_at' => '2025-01-07 17:15:45',
                'updated_at' => '2025-01-07 17:15:45',
            ),
            10 =>
            array (
                'id' => 11,
                'form_type' => 'App\\Models\\Xlsforms\\Xlsform',
                'form_id' => 9,
                'label' => 'P1 Test Team 2 custom module',
                'name' => 'P1 Test Team 2 custom module',
                'created_at' => '2025-01-07 17:15:45',
                'updated_at' => '2025-01-07 17:15:45',
            ),
            11 =>
            array (
                'id' => 12,
                'form_type' => 'App\\Models\\Xlsforms\\Xlsform',
                'form_id' => 10,
                'label' => 'Non Program Test Team custom module',
                'name' => 'Non Program Test Team custom module',
                'created_at' => '2025-01-07 17:15:45',
                'updated_at' => '2025-01-07 17:15:45',
            ),
            12 =>
            array (
                'id' => 13,
                'form_type' => 'App\\Models\\Xlsforms\\Xlsform',
                'form_id' => 11,
                'label' => 'P1 Test Team custom module',
                'name' => 'P1 Test Team custom module',
                'created_at' => '2025-01-07 17:15:46',
                'updated_at' => '2025-01-07 17:15:46',
            ),
            13 =>
            array (
                'id' => 14,
                'form_type' => 'App\\Models\\Xlsforms\\Xlsform',
                'form_id' => 12,
                'label' => 'P1 Test Team 2 custom module',
                'name' => 'P1 Test Team 2 custom module',
                'created_at' => '2025-01-07 17:15:46',
                'updated_at' => '2025-01-07 17:15:46',
            ),
            14 =>
            array (
                'id' => 15,
                'form_type' => 'App\\Models\\Xlsforms\\Xlsform',
                'form_id' => 13,
                'label' => 'Non Program Test Team custom module',
                'name' => 'Non Program Test Team custom module',
                'created_at' => '2025-01-07 17:15:46',
                'updated_at' => '2025-01-07 17:15:46',
            ),
        ));


    }
}
