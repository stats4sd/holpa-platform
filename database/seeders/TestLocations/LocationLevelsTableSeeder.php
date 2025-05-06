<?php

namespace Database\Seeders\TestLocations;

use Illuminate\Database\Seeder;

class LocationLevelsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('location_levels')->delete();

        \DB::table('location_levels')->insert([
            [
                'id' => 1,
                'owner_id' => 1,
                'parent_id' => NULL,
                'name' => 'district',
                'slug' => 'district',
                'description' => NULL,
                'has_farms' => 0,
                'created_at' => '2025-04-17 14:17:03',
                'updated_at' => '2025-04-17 14:17:03',
            ],
            [
                'id' => 2,
                'owner_id' => 1,
                'parent_id' => 1,
                'name' => 'sub-district',
                'slug' => 'sub-district',
                'description' => NULL,
                'has_farms' => 0,
                'created_at' => '2025-04-17 14:17:07',
                'updated_at' => '2025-04-17 14:17:07',
            ],
            [
                'id' => 3,
                'owner_id' => 1,
                'parent_id' => 2,
                'name' => 'village',
                'slug' => 'village',
                'description' => NULL,
                'has_farms' => 1,
                'created_at' => '2025-04-17 14:17:11',
                'updated_at' => '2025-04-17 14:17:44',
            ],
        ]);

        \DB::table('location_levels')->insert([
            [
                'id' => 4,
                'owner_id' => 3,
                'parent_id' => NULL,
                'name' => 'district',
                'slug' => 'district',
                'has_farms' => 0,
            ],
            [
                'id' => 5,
                'owner_id' => 3,
                'parent_id' => 4,
                'name' => 'sub-district',
                'slug' => 'sub-district',
                'has_farms' => 0,
            ],
            [
                'id' => 6,
                'owner_id' => 3,
                'parent_id' => 5,
                'name' => 'village',
                'slug' => 'village',
                'has_farms' => 1,
            ],
        ]);


    }
}
