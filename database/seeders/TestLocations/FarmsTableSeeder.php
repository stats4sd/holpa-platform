<?php

namespace Database\Seeders\TestLocations;

use Illuminate\Database\Seeder;

class FarmsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('farms')->delete();

        \DB::table('farms')->insert(array (
            0 =>
            array (
                'id' => 1,
                'owner_id' => 1,
                'location_id' => 3,
                'team_code' => '1',
                'identifiers' => '{"name": "Farm 1", "name_en": "Farm 1"}',
                'latitude' => NULL,
                'longitude' => NULL,
                'altitude' => NULL,
                'accuracy' => NULL,
                'household_form_completed' => 0,
                'fieldwork_form_completed' => 0,
                'properties' => '[]',
                'created_at' => '2025-04-17 14:21:45',
                'updated_at' => '2025-04-17 14:21:45',
            ),
            1 =>
            array (
                'id' => 2,
                'owner_id' => 1,
                'location_id' => 3,
                'team_code' => '2',
                'identifiers' => '{"name": "Farm 2", "name_en": "Farm 2"}',
                'latitude' => NULL,
                'longitude' => NULL,
                'altitude' => NULL,
                'accuracy' => NULL,
                'household_form_completed' => 0,
                'fieldwork_form_completed' => 0,
                'properties' => '[]',
                'created_at' => '2025-04-17 14:21:45',
                'updated_at' => '2025-04-17 14:21:45',
            ),
            2 =>
            array (
                'id' => 3,
                'owner_id' => 1,
                'location_id' => 9,
                'team_code' => '3',
                'identifiers' => '{"name": "Farm 3", "name_en": "Farm 3"}',
                'latitude' => NULL,
                'longitude' => NULL,
                'altitude' => NULL,
                'accuracy' => NULL,
                'household_form_completed' => 0,
                'fieldwork_form_completed' => 0,
                'properties' => '[]',
                'created_at' => '2025-04-17 14:21:45',
                'updated_at' => '2025-04-17 14:21:45',
            ),
            3 =>
            array (
                'id' => 4,
                'owner_id' => 1,
                'location_id' => 9,
                'team_code' => '4',
                'identifiers' => '{"name": "Farm 4", "name_en": "Farm 4"}',
                'latitude' => NULL,
                'longitude' => NULL,
                'altitude' => NULL,
                'accuracy' => NULL,
                'household_form_completed' => 0,
                'fieldwork_form_completed' => 0,
                'properties' => '[]',
                'created_at' => '2025-04-17 14:21:45',
                'updated_at' => '2025-04-17 14:21:45',
            ),
            4 =>
            array (
                'id' => 5,
                'owner_id' => 1,
                'location_id' => 15,
                'team_code' => '5',
                'identifiers' => '{"name": "Farm 5", "name_en": "Farm 5"}',
                'latitude' => NULL,
                'longitude' => NULL,
                'altitude' => NULL,
                'accuracy' => NULL,
                'household_form_completed' => 0,
                'fieldwork_form_completed' => 0,
                'properties' => '[]',
                'created_at' => '2025-04-17 14:21:45',
                'updated_at' => '2025-04-17 14:21:45',
            ),
            5 =>
            array (
                'id' => 6,
                'owner_id' => 1,
                'location_id' => 15,
                'team_code' => '6',
                'identifiers' => '{"name": "Farm 6", "name_en": "Farm 6"}',
                'latitude' => NULL,
                'longitude' => NULL,
                'altitude' => NULL,
                'accuracy' => NULL,
                'household_form_completed' => 0,
                'fieldwork_form_completed' => 0,
                'properties' => '[]',
                'created_at' => '2025-04-17 14:21:45',
                'updated_at' => '2025-04-17 14:21:45',
            ),
            6 =>
            array (
                'id' => 7,
                'owner_id' => 1,
                'location_id' => 21,
                'team_code' => '7',
                'identifiers' => '{"name": "Farm 7", "name_en": "Farm 7"}',
                'latitude' => NULL,
                'longitude' => NULL,
                'altitude' => NULL,
                'accuracy' => NULL,
                'household_form_completed' => 0,
                'fieldwork_form_completed' => 0,
                'properties' => '[]',
                'created_at' => '2025-04-17 14:21:45',
                'updated_at' => '2025-04-17 14:21:45',
            ),
            7 =>
            array (
                'id' => 8,
                'owner_id' => 1,
                'location_id' => 21,
                'team_code' => '8',
                'identifiers' => '{"name": "Farm 8", "name_en": "Farm 8"}',
                'latitude' => NULL,
                'longitude' => NULL,
                'altitude' => NULL,
                'accuracy' => NULL,
                'household_form_completed' => 0,
                'fieldwork_form_completed' => 0,
                'properties' => '[]',
                'created_at' => '2025-04-17 14:21:45',
                'updated_at' => '2025-04-17 14:21:45',
            ),
            8 =>
            array (
                'id' => 9,
                'owner_id' => 1,
                'location_id' => 27,
                'team_code' => '9',
                'identifiers' => '{"name": "Farm 9", "name_en": "Farm 9"}',
                'latitude' => NULL,
                'longitude' => NULL,
                'altitude' => NULL,
                'accuracy' => NULL,
                'household_form_completed' => 0,
                'fieldwork_form_completed' => 0,
                'properties' => '[]',
                'created_at' => '2025-04-17 14:21:45',
                'updated_at' => '2025-04-17 14:21:45',
            ),
            9 =>
            array (
                'id' => 10,
                'owner_id' => 1,
                'location_id' => 27,
                'team_code' => '10',
                'identifiers' => '{"name": "Farm 10", "name_en": "Farm 10"}',
                'latitude' => NULL,
                'longitude' => NULL,
                'altitude' => NULL,
                'accuracy' => NULL,
                'household_form_completed' => 0,
                'fieldwork_form_completed' => 0,
                'properties' => '[]',
                'created_at' => '2025-04-17 14:21:45',
                'updated_at' => '2025-04-17 14:21:45',
            ),
            10 =>
            array (
                'id' => 11,
                'owner_id' => 1,
                'location_id' => 33,
                'team_code' => '11',
                'identifiers' => '{"name": "Farm 11", "name_en": "Farm 11"}',
                'latitude' => NULL,
                'longitude' => NULL,
                'altitude' => NULL,
                'accuracy' => NULL,
                'household_form_completed' => 0,
                'fieldwork_form_completed' => 0,
                'properties' => '[]',
                'created_at' => '2025-04-17 14:21:45',
                'updated_at' => '2025-04-17 14:21:45',
            ),
            11 =>
            array (
                'id' => 12,
                'owner_id' => 1,
                'location_id' => 33,
                'team_code' => '12',
                'identifiers' => '{"name": "Farm 12", "name_en": "Farm 12"}',
                'latitude' => NULL,
                'longitude' => NULL,
                'altitude' => NULL,
                'accuracy' => NULL,
                'household_form_completed' => 0,
                'fieldwork_form_completed' => 0,
                'properties' => '[]',
                'created_at' => '2025-04-17 14:21:45',
                'updated_at' => '2025-04-17 14:21:45',
            ),
            12 =>
            array (
                'id' => 13,
                'owner_id' => 1,
                'location_id' => 39,
                'team_code' => '13',
                'identifiers' => '{"name": "Farm 13", "name_en": "Farm 13"}',
                'latitude' => NULL,
                'longitude' => NULL,
                'altitude' => NULL,
                'accuracy' => NULL,
                'household_form_completed' => 0,
                'fieldwork_form_completed' => 0,
                'properties' => '[]',
                'created_at' => '2025-04-17 14:21:45',
                'updated_at' => '2025-04-17 14:21:45',
            ),
            13 =>
            array (
                'id' => 14,
                'owner_id' => 1,
                'location_id' => 39,
                'team_code' => '14',
                'identifiers' => '{"name": "Farm 14", "name_en": "Farm 14"}',
                'latitude' => NULL,
                'longitude' => NULL,
                'altitude' => NULL,
                'accuracy' => NULL,
                'household_form_completed' => 0,
                'fieldwork_form_completed' => 0,
                'properties' => '[]',
                'created_at' => '2025-04-17 14:21:45',
                'updated_at' => '2025-04-17 14:21:45',
            ),
            14 =>
            array (
                'id' => 15,
                'owner_id' => 1,
                'location_id' => 45,
                'team_code' => '15',
                'identifiers' => '{"name": "Farm 15", "name_en": "Farm 15"}',
                'latitude' => NULL,
                'longitude' => NULL,
                'altitude' => NULL,
                'accuracy' => NULL,
                'household_form_completed' => 0,
                'fieldwork_form_completed' => 0,
                'properties' => '[]',
                'created_at' => '2025-04-17 14:21:45',
                'updated_at' => '2025-04-17 14:21:45',
            ),
            15 =>
            array (
                'id' => 16,
                'owner_id' => 1,
                'location_id' => 45,
                'team_code' => '16',
                'identifiers' => '{"name": "Farm 16", "name_en": "Farm 16"}',
                'latitude' => NULL,
                'longitude' => NULL,
                'altitude' => NULL,
                'accuracy' => NULL,
                'household_form_completed' => 0,
                'fieldwork_form_completed' => 0,
                'properties' => '[]',
                'created_at' => '2025-04-17 14:21:45',
                'updated_at' => '2025-04-17 14:21:45',
            ),
        ));


    }
}
