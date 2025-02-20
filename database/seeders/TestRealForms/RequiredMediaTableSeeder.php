<?php

namespace Database\Seeders\TestRealForms;

use Illuminate\Database\Seeder;

class RequiredMediaTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('required_media')->delete();

        \DB::table('required_media')->insert(array (
            0 =>
            array (
                'id' => 1,
                'dataset_id' => NULL,
                'xlsform_template_id' => 1,
                'name' => 'area_units.csv',
                'type' => 'file',
                'is_static' => 1,
                'exists_on_odk' => 0,
                'choice_list_id' => NULL,
                'updated_during_import' => 1,
                'created_at' => '2025-02-20 15:41:00',
                'updated_at' => '2025-02-20 15:41:00',
            ),
            1 =>
            array (
                'id' => 2,
                'dataset_id' => NULL,
                'xlsform_template_id' => 1,
                'name' => 'fert_units.csv',
                'type' => 'file',
                'is_static' => 1,
                'exists_on_odk' => 0,
                'choice_list_id' => NULL,
                'updated_during_import' => 1,
                'created_at' => '2025-02-20 15:41:00',
                'updated_at' => '2025-02-20 15:41:00',
            ),
            2 =>
            array (
                'id' => 3,
                'dataset_id' => NULL,
                'xlsform_template_id' => 1,
                'name' => 'yield_units.csv',
                'type' => 'file',
                'is_static' => 1,
                'exists_on_odk' => 0,
                'choice_list_id' => NULL,
                'updated_during_import' => 1,
                'created_at' => '2025-02-20 15:41:00',
                'updated_at' => '2025-02-20 15:41:00',
            ),
            3 =>
            array (
                'id' => 4,
                'dataset_id' => NULL,
                'xlsform_template_id' => 2,
                'name' => 'appearance.png',
                'type' => 'image',
                'is_static' => 1,
                'exists_on_odk' => 0,
                'choice_list_id' => NULL,
                'updated_during_import' => 1,
                'created_at' => '2025-02-20 15:51:44',
                'updated_at' => '2025-02-20 15:51:44',
            ),
            4 =>
            array (
                'id' => 5,
                'dataset_id' => NULL,
                'xlsform_template_id' => 2,
                'name' => 'disease_incidence.png',
                'type' => 'image',
                'is_static' => 1,
                'exists_on_odk' => 0,
                'choice_list_id' => NULL,
                'updated_during_import' => 1,
                'created_at' => '2025-02-20 15:51:44',
                'updated_at' => '2025-02-20 15:51:44',
            ),
            5 =>
            array (
                'id' => 6,
                'dataset_id' => NULL,
                'xlsform_template_id' => 2,
                'name' => 'insect_incidence.png',
                'type' => 'image',
                'is_static' => 1,
                'exists_on_odk' => 0,
                'choice_list_id' => NULL,
                'updated_during_import' => 1,
                'created_at' => '2025-02-20 15:51:44',
                'updated_at' => '2025-02-20 15:51:44',
            ),
            6 =>
            array (
                'id' => 7,
                'dataset_id' => NULL,
                'xlsform_template_id' => 2,
                'name' => 'soil_sample.png',
                'type' => 'image',
                'is_static' => 1,
                'exists_on_odk' => 0,
                'choice_list_id' => NULL,
                'updated_during_import' => 1,
                'created_at' => '2025-02-20 15:51:44',
                'updated_at' => '2025-02-20 15:51:44',
            ),
        ));


    }
}
