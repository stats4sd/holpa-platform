<?php

namespace Database\Seeders\TestLocations;

use Illuminate\Database\Seeder;

class LocationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('locations')->delete();

        \DB::table('locations')->insert([
            [
                'id' => 1,
                'owner_id' => 1,
                'location_level_id' => 1,
                'parent_id' => NULL,
                'code' => '1',
                'name' => 'District One',
                'description' => NULL,
                'created_at' => '2025-04-17 14:21:17',
                'updated_at' => '2025-04-17 14:21:17',
            ], [
                'id' => 2,
                'owner_id' => 1,
                'location_level_id' => 2,
                'parent_id' => 1,
                'code' => '3',
                'name' => 'Sub 1.1',
                'description' => NULL,
                'created_at' => '2025-04-17 14:21:17',
                'updated_at' => '2025-04-17 14:21:17',
            ], [
                'id' => 3,
                'owner_id' => 1,
                'location_level_id' => 3,
                'parent_id' => 2,
                'code' => '7',
                'name' => 'Seven',
                'description' => NULL,
                'created_at' => '2025-04-17 14:21:17',
                'updated_at' => '2025-04-17 14:21:17',
            ], [
                'id' => 9,
                'owner_id' => 1,
                'location_level_id' => 3,
                'parent_id' => 2,
                'code' => '8',
                'name' => 'Eight',
                'description' => NULL,
                'created_at' => '2025-04-17 14:21:17',
                'updated_at' => '2025-04-17 14:21:17',
            ], [
                'id' => 14,
                'owner_id' => 1,
                'location_level_id' => 2,
                'parent_id' => 1,
                'code' => '4',
                'name' => 'Sub 1.2',
                'description' => NULL,
                'created_at' => '2025-04-17 14:21:17',
                'updated_at' => '2025-04-17 14:21:17',
            ], [
                'id' => 15,
                'owner_id' => 1,
                'location_level_id' => 3,
                'parent_id' => 14,
                'code' => '9',
                'name' => 'Nine',
                'description' => NULL,
                'created_at' => '2025-04-17 14:21:17',
                'updated_at' => '2025-04-17 14:21:17',
            ], [
                'id' => 21,
                'owner_id' => 1,
                'location_level_id' => 3,
                'parent_id' => 14,
                'code' => '10',
                'name' => 'Ten',
                'description' => NULL,
                'created_at' => '2025-04-17 14:21:17',
                'updated_at' => '2025-04-17 14:21:17',
            ], [
                'id' => 25,
                'owner_id' => 1,
                'location_level_id' => 1,
                'parent_id' => NULL,
                'code' => '2',
                'name' => 'District Two',
                'description' => NULL,
                'created_at' => '2025-04-17 14:21:17',
                'updated_at' => '2025-04-17 14:21:17',
            ], [
                'id' => 26,
                'owner_id' => 1,
                'location_level_id' => 2,
                'parent_id' => 25,
                'code' => '5',
                'name' => 'Sub 2.1',
                'description' => NULL,
                'created_at' => '2025-04-17 14:21:17',
                'updated_at' => '2025-04-17 14:21:17',
            ], [
                'id' => 27,
                'owner_id' => 1,
                'location_level_id' => 3,
                'parent_id' => 26,
                'code' => '11',
                'name' => 'Eleven',
                'description' => NULL,
                'created_at' => '2025-04-17 14:21:17',
                'updated_at' => '2025-04-17 14:21:17',
            ],
            [
                'id' => 33,
                'owner_id' => 1,
                'location_level_id' => 3,
                'parent_id' => 26,
                'code' => '12',
                'name' => 'Twelve',
                'description' => NULL,
                'created_at' => '2025-04-17 14:21:17',
                'updated_at' => '2025-04-17 14:21:17',
            ],
            [
                'id' => 38,
                'owner_id' => 1,
                'location_level_id' => 2,
                'parent_id' => 25,
                'code' => '6',
                'name' => 'Sub 2.2',
                'description' => NULL,
                'created_at' => '2025-04-17 14:21:17',
                'updated_at' => '2025-04-17 14:21:17',
            ],
            [
                'id' => 39,
                'owner_id' => 1,
                'location_level_id' => 3,
                'parent_id' => 38,
                'code' => '13',
                'name' => 'Thirteen',
                'description' => NULL,
                'created_at' => '2025-04-17 14:21:17',
                'updated_at' => '2025-04-17 14:21:17',
            ],
            [
                'id' => 45,
                'owner_id' => 1,
                'location_level_id' => 3,
                'parent_id' => 38,
                'code' => '14',
                'name' => 'Fourteen',
                'description' => NULL,
                'created_at' => '2025-04-17 14:21:17',
                'updated_at' => '2025-04-17 14:21:17',
            ],

        ]);

        \DB::table('locations')->insert([
            [
                'id' => 46,
                'owner_id' => 3,
                'location_level_id' => 4,
                'parent_id' => NULL,
                'code' => '1',
                'name' => 'District 1',
            ],
            [
                'id' => 47,
                'owner_id' => 3,
                'location_level_id' => 5,
                'parent_id' => 46,
                'code' => '2',
                'name' => 'Sub District 2',
            ],
            [
                'id' => 48,
                'owner_id' => 3,
                'location_level_id' => 5,
                'parent_id' => 46,
                'code' => '3',
                'name' => 'Sub District 3',
            ],
            [
                'id' => 49,
                'owner_id' => 3,
                'location_level_id' => 5,
                'parent_id' => 46,
                'code' => '4',
                'name' => 'Sub District 4',
            ],

            [
                'id' => 50,
                'owner_id' => 3,
                'location_level_id' => 6,
                'parent_id' => 47,
                'code' => '5',
                'name' => 'Village 1',
            ],
            [
                'id' => 51,
                'owner_id' => 3,
                'location_level_id' => 6,
                'parent_id' => 47,
                'code' => '6',
                'name' => 'Village 2',
            ],
            [
                'id' => 52,
                'owner_id' => 3,
                'location_level_id' => 6,
                'parent_id' => 48,
                'code' => '7',
                'name' => 'Village 3',
            ],
            [
                'id' => 53,
                'owner_id' => 3,
                'location_level_id' => 6,
                'parent_id' => 48,
                'code' => '8',
                'name' => 'Village 4',
            ],
            [
                'id' => 54,
                'owner_id' => 3,
                'location_level_id' => 6,
                'parent_id' => 49,
                'code' => '9',
                'name' => 'Village 5',
            ],
            [
                'id' => 55,
                'owner_id' => 3,
                'location_level_id' => 6,
                'parent_id' => 49,
                'code' => '10',
                'name' => 'Village 6',
            ],
            [
                'id' => 56,
                'owner_id' => 3,
                'location_level_id' => 6,
                'parent_id' => 49,
                'code' => '11',
                'name' => 'Village 7',
            ],
            [
                'id' => 57,
                'owner_id' => 3,
                'location_level_id' => 6,
                'parent_id' => 49,
                'code' => '12',
                'name' => 'Village 8',
            ],
            [
                'id' => 58,
                'owner_id' => 3,
                'location_level_id' => 6,
                'parent_id' => 49,
                'code' => '13',
                'name' => 'Village 9',
            ],
            [
                'id' => 59,
                'owner_id' => 3,
                'location_level_id' => 6,
                'parent_id' => 49,
                'code' => '14',
                'name' => 'Village 10',
            ],

        ]);


    }
}
