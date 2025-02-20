<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LocalesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('locales')->delete();
        
        \DB::table('locales')->insert(array (
            0 => 
            array (
                'id' => 1,
                'language_id' => 41,
                'creator_type' => NULL,
                'creator_id' => NULL,
                'is_default' => 1,
                'description' => NULL,
                'created_at' => '2025-02-20 15:44:01',
                'updated_at' => '2025-02-20 15:44:01',
            ),
        ));
        
        
    }
}