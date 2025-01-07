<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ChoiceListEntriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('choice_list_entries')->delete();
        
        \DB::table('choice_list_entries')->insert(array (
            0 => 
            array (
                'id' => 1,
                'choice_list_id' => 1,
                'owner_type' => NULL,
                'owner_id' => NULL,
                'name' => '1',
                'properties' => '[]',
                'cascade_filter' => NULL,
                'updated_during_import' => 0,
                'created_at' => '2025-01-07 16:20:08',
                'updated_at' => '2025-01-07 16:20:16',
            ),
            1 => 
            array (
                'id' => 2,
                'choice_list_id' => 1,
                'owner_type' => NULL,
                'owner_id' => NULL,
                'name' => '0',
                'properties' => '[]',
                'cascade_filter' => NULL,
                'updated_during_import' => 0,
                'created_at' => '2025-01-07 16:20:08',
                'updated_at' => '2025-01-07 16:20:16',
            ),
            2 => 
            array (
                'id' => 3,
                'choice_list_id' => 2,
                'owner_type' => NULL,
                'owner_id' => NULL,
                'name' => 'black_tea',
                'properties' => '{"localisable": "yes"}',
                'cascade_filter' => NULL,
                'updated_during_import' => 0,
                'created_at' => '2025-01-07 16:20:09',
                'updated_at' => '2025-01-07 16:20:16',
            ),
            3 => 
            array (
                'id' => 4,
                'choice_list_id' => 2,
                'owner_type' => NULL,
                'owner_id' => NULL,
                'name' => 'green_tea',
                'properties' => '{"localisable": "yes"}',
                'cascade_filter' => NULL,
                'updated_during_import' => 0,
                'created_at' => '2025-01-07 16:20:09',
                'updated_at' => '2025-01-07 16:20:16',
            ),
            4 => 
            array (
                'id' => 5,
                'choice_list_id' => 2,
                'owner_type' => NULL,
                'owner_id' => NULL,
                'name' => 'cola',
                'properties' => '{"localisable": "yes"}',
                'cascade_filter' => NULL,
                'updated_during_import' => 0,
                'created_at' => '2025-01-07 16:20:09',
                'updated_at' => '2025-01-07 16:20:16',
            ),
            5 => 
            array (
                'id' => 6,
                'choice_list_id' => 2,
                'owner_type' => NULL,
                'owner_id' => NULL,
                'name' => 'diet_cola',
                'properties' => '{"localisable": "yes"}',
                'cascade_filter' => NULL,
                'updated_during_import' => 0,
                'created_at' => '2025-01-07 16:20:09',
                'updated_at' => '2025-01-07 16:20:16',
            ),
            6 => 
            array (
                'id' => 7,
                'choice_list_id' => 2,
                'owner_type' => NULL,
                'owner_id' => NULL,
                'name' => 'juice',
                'properties' => '{"localisable": "yes"}',
                'cascade_filter' => NULL,
                'updated_during_import' => 0,
                'created_at' => '2025-01-07 16:20:09',
                'updated_at' => '2025-01-07 16:20:16',
            ),
            7 => 
            array (
                'id' => 8,
                'choice_list_id' => 3,
                'owner_type' => NULL,
                'owner_id' => NULL,
                'name' => 'very_good',
                'properties' => '[]',
                'cascade_filter' => NULL,
                'updated_during_import' => 0,
                'created_at' => '2025-01-07 16:20:09',
                'updated_at' => '2025-01-07 16:20:16',
            ),
            8 => 
            array (
                'id' => 9,
                'choice_list_id' => 3,
                'owner_type' => NULL,
                'owner_id' => NULL,
                'name' => 'good',
                'properties' => '[]',
                'cascade_filter' => NULL,
                'updated_during_import' => 0,
                'created_at' => '2025-01-07 16:20:09',
                'updated_at' => '2025-01-07 16:20:16',
            ),
            9 => 
            array (
                'id' => 10,
                'choice_list_id' => 3,
                'owner_type' => NULL,
                'owner_id' => NULL,
                'name' => 'neutral',
                'properties' => '[]',
                'cascade_filter' => NULL,
                'updated_during_import' => 0,
                'created_at' => '2025-01-07 16:20:09',
                'updated_at' => '2025-01-07 16:20:16',
            ),
            10 => 
            array (
                'id' => 11,
                'choice_list_id' => 3,
                'owner_type' => NULL,
                'owner_id' => NULL,
                'name' => 'bad',
                'properties' => '[]',
                'cascade_filter' => NULL,
                'updated_during_import' => 0,
                'created_at' => '2025-01-07 16:20:09',
                'updated_at' => '2025-01-07 16:20:16',
            ),
            11 => 
            array (
                'id' => 12,
                'choice_list_id' => 3,
                'owner_type' => NULL,
                'owner_id' => NULL,
                'name' => 'very_bad',
                'properties' => '[]',
                'cascade_filter' => NULL,
                'updated_during_import' => 0,
                'created_at' => '2025-01-07 16:20:09',
                'updated_at' => '2025-01-07 16:20:16',
            ),
            12 => 
            array (
                'id' => 13,
                'choice_list_id' => 4,
                'owner_type' => NULL,
                'owner_id' => NULL,
                'name' => '1',
                'properties' => '[]',
                'cascade_filter' => NULL,
                'updated_during_import' => 0,
                'created_at' => '2025-01-07 16:20:10',
                'updated_at' => '2025-01-07 16:20:16',
            ),
            13 => 
            array (
                'id' => 14,
                'choice_list_id' => 4,
                'owner_type' => NULL,
                'owner_id' => NULL,
                'name' => '0',
                'properties' => '[]',
                'cascade_filter' => NULL,
                'updated_during_import' => 0,
                'created_at' => '2025-01-07 16:20:10',
                'updated_at' => '2025-01-07 16:20:16',
            ),
            14 => 
            array (
                'id' => 15,
                'choice_list_id' => 5,
                'owner_type' => NULL,
                'owner_id' => NULL,
                'name' => '1',
                'properties' => '[]',
                'cascade_filter' => NULL,
                'updated_during_import' => 0,
                'created_at' => '2025-01-07 16:20:58',
                'updated_at' => '2025-01-07 16:21:05',
            ),
            15 => 
            array (
                'id' => 16,
                'choice_list_id' => 5,
                'owner_type' => NULL,
                'owner_id' => NULL,
                'name' => '0',
                'properties' => '[]',
                'cascade_filter' => NULL,
                'updated_during_import' => 0,
                'created_at' => '2025-01-07 16:20:58',
                'updated_at' => '2025-01-07 16:21:05',
            ),
            16 => 
            array (
                'id' => 17,
                'choice_list_id' => 6,
                'owner_type' => NULL,
                'owner_id' => NULL,
                'name' => 'clay',
                'properties' => '[]',
                'cascade_filter' => NULL,
                'updated_during_import' => 0,
                'created_at' => '2025-01-07 16:20:59',
                'updated_at' => '2025-01-07 16:21:05',
            ),
            17 => 
            array (
                'id' => 18,
                'choice_list_id' => 6,
                'owner_type' => NULL,
                'owner_id' => NULL,
                'name' => 'loam',
                'properties' => '[]',
                'cascade_filter' => NULL,
                'updated_during_import' => 0,
                'created_at' => '2025-01-07 16:20:59',
                'updated_at' => '2025-01-07 16:21:05',
            ),
            18 => 
            array (
                'id' => 19,
                'choice_list_id' => 6,
                'owner_type' => NULL,
                'owner_id' => NULL,
                'name' => 'sand',
                'properties' => '[]',
                'cascade_filter' => NULL,
                'updated_during_import' => 0,
                'created_at' => '2025-01-07 16:20:59',
                'updated_at' => '2025-01-07 16:21:05',
            ),
            19 => 
            array (
                'id' => 20,
                'choice_list_id' => 6,
                'owner_type' => NULL,
                'owner_id' => NULL,
                'name' => 'silt',
                'properties' => '[]',
                'cascade_filter' => NULL,
                'updated_during_import' => 0,
                'created_at' => '2025-01-07 16:20:59',
                'updated_at' => '2025-01-07 16:21:05',
            ),
            20 => 
            array (
                'id' => 21,
                'choice_list_id' => 6,
                'owner_type' => NULL,
                'owner_id' => NULL,
                'name' => 'other',
                'properties' => '[]',
                'cascade_filter' => NULL,
                'updated_during_import' => 0,
                'created_at' => '2025-01-07 16:20:59',
                'updated_at' => '2025-01-07 16:21:05',
            ),
        ));
        
        
    }
}