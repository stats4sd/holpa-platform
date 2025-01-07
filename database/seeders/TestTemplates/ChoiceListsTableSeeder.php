<?php

namespace Database\Seeders\TestTemplates;

use Illuminate\Database\Seeder;

class ChoiceListsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('choice_lists')->delete();

        \DB::table('choice_lists')->insert(array (
            0 =>
            array (
                'id' => 1,
                'xlsform_module_version_id' => 3,
                'list_name' => 'yn',
                'description' => NULL,
                'is_localisable' => 0,
                'is_dataset' => 0,
                'can_be_hidden_from_context' => 0,
                'has_custom_handling' => 0,
                'properties' => NULL,
                'created_at' => '2025-01-07 16:20:05',
                'updated_at' => '2025-01-07 16:20:05',
            ),
            1 =>
            array (
                'id' => 2,
                'xlsform_module_version_id' => 4,
                'list_name' => 'drinks',
                'description' => NULL,
                'is_localisable' => 0,
                'is_dataset' => 0,
                'can_be_hidden_from_context' => 0,
                'has_custom_handling' => 0,
                'properties' => NULL,
                'created_at' => '2025-01-07 16:20:06',
                'updated_at' => '2025-01-07 16:20:06',
            ),
            2 =>
            array (
                'id' => 3,
                'xlsform_module_version_id' => 4,
                'list_name' => 'lk',
                'description' => NULL,
                'is_localisable' => 0,
                'is_dataset' => 0,
                'can_be_hidden_from_context' => 0,
                'has_custom_handling' => 0,
                'properties' => NULL,
                'created_at' => '2025-01-07 16:20:06',
                'updated_at' => '2025-01-07 16:20:06',
            ),
            3 =>
            array (
                'id' => 4,
                'xlsform_module_version_id' => 5,
                'list_name' => 'yn',
                'description' => NULL,
                'is_localisable' => 0,
                'is_dataset' => 0,
                'can_be_hidden_from_context' => 0,
                'has_custom_handling' => 0,
                'properties' => NULL,
                'created_at' => '2025-01-07 16:20:07',
                'updated_at' => '2025-01-07 16:20:07',
            ),
            4 =>
            array (
                'id' => 5,
                'xlsform_module_version_id' => 9,
                'list_name' => 'yn',
                'description' => NULL,
                'is_localisable' => 0,
                'is_dataset' => 0,
                'can_be_hidden_from_context' => 0,
                'has_custom_handling' => 0,
                'properties' => NULL,
                'created_at' => '2025-01-07 16:20:55',
                'updated_at' => '2025-01-07 16:20:55',
            ),
            5 =>
            array (
                'id' => 6,
                'xlsform_module_version_id' => 8,
                'list_name' => 'soils',
                'description' => NULL,
                'is_localisable' => 0,
                'is_dataset' => 0,
                'can_be_hidden_from_context' => 0,
                'has_custom_handling' => 0,
                'properties' => NULL,
                'created_at' => '2025-01-07 16:20:56',
                'updated_at' => '2025-01-07 16:20:56',
            ),
        ));


    }
}
