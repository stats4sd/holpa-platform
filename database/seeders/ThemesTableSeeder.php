<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ThemesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('themes')->delete();
        
        \DB::table('themes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'module' => 'Context',
                'domain' => NULL,
                'name' => 'Location',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            1 => 
            array (
                'id' => 2,
                'module' => 'Context',
                'domain' => NULL,
                'name' => 'Respondent characteristics',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            2 => 
            array (
                'id' => 3,
                'module' => 'Context',
                'domain' => NULL,
                'name' => 'Household characteristics',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            3 => 
            array (
                'id' => 4,
                'module' => 'Context',
                'domain' => NULL,
                'name' => 'Farm characteristics',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            4 => 
            array (
                'id' => 5,
                'module' => 'Context',
                'domain' => NULL,
                'name' => 'Production systems',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            5 => 
            array (
                'id' => 6,
                'module' => 'Context',
                'domain' => NULL,
                'name' => 'Climate change',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            6 => 
            array (
                'id' => 7,
                'module' => 'Context',
                'domain' => NULL,
                'name' => 'Movitation to transition',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            7 => 
            array (
                'id' => 8,
                'module' => 'Agroecology',
                'domain' => NULL,
                'name' => 'Recycling',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            8 => 
            array (
                'id' => 9,
                'module' => 'Agroecology',
                'domain' => NULL,
                'name' => 'Input reduction',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            9 => 
            array (
                'id' => 10,
                'module' => 'Agroecology',
                'domain' => NULL,
                'name' => 'Soil health',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            10 => 
            array (
                'id' => 11,
                'module' => 'Agroecology',
                'domain' => NULL,
                'name' => 'Animal health',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            11 => 
            array (
                'id' => 12,
                'module' => 'Agroecology',
                'domain' => NULL,
                'name' => 'Biodiversity',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            12 => 
            array (
                'id' => 13,
                'module' => 'Agroecology',
                'domain' => NULL,
                'name' => 'Synergies',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            13 => 
            array (
                'id' => 14,
                'module' => 'Agroecology',
                'domain' => NULL,
                'name' => 'Economic diversification',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            14 => 
            array (
                'id' => 15,
                'module' => 'Agroecology',
                'domain' => NULL,
                'name' => 'Knowledge co-creation',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            15 => 
            array (
                'id' => 16,
                'module' => 'Agroecology',
                'domain' => NULL,
                'name' => 'Governance',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            16 => 
            array (
                'id' => 17,
                'module' => 'Agroecology',
                'domain' => NULL,
                'name' => 'Social values and diets',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            17 => 
            array (
                'id' => 18,
                'module' => 'Agroecology',
                'domain' => NULL,
                'name' => 'Fairness',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            18 => 
            array (
                'id' => 19,
                'module' => 'Agroecology',
                'domain' => NULL,
                'name' => 'Connectivity',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            19 => 
            array (
                'id' => 20,
                'module' => 'Agroecology',
                'domain' => NULL,
                'name' => 'Participation',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            20 => 
            array (
                'id' => 21,
                'module' => 'Performance',
                'domain' => 'Agricultural',
                'name' => 'Crop health',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            21 => 
            array (
                'id' => 22,
                'module' => 'Performance',
                'domain' => 'Agricultural',
                'name' => 'Animal health',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            22 => 
            array (
                'id' => 23,
                'module' => 'Performance',
                'domain' => 'Agricultural',
                'name' => 'Soil health',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            23 => 
            array (
                'id' => 24,
                'module' => 'Performance',
                'domain' => 'Agricultural',
                'name' => 'Nutrient use ',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            24 => 
            array (
                'id' => 25,
                'module' => 'Performance',
                'domain' => 'Environmental',
                'name' => 'Biodiversity',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            25 => 
            array (
                'id' => 26,
                'module' => 'Performance',
                'domain' => 'Environmental',
                'name' => 'Agrobiodiversity',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            26 => 
            array (
                'id' => 27,
                'module' => 'Performance',
                'domain' => 'Environmental',
                'name' => 'Landscape complexity',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            27 => 
            array (
                'id' => 28,
                'module' => 'Performance',
                'domain' => 'Environmental',
                'name' => 'Climate mitigation',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            28 => 
            array (
                'id' => 29,
                'module' => 'Performance',
                'domain' => 'Environmental',
                'name' => 'Water',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            29 => 
            array (
                'id' => 30,
                'module' => 'Performance',
                'domain' => 'Environmental',
                'name' => 'Energy use',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            30 => 
            array (
                'id' => 31,
                'module' => 'Performance',
                'domain' => 'Economic',
                'name' => 'Income',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            31 => 
            array (
                'id' => 32,
                'module' => 'Performance',
                'domain' => 'Economic',
                'name' => 'Agricultural productivity',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            32 => 
            array (
                'id' => 33,
                'module' => 'Performance',
                'domain' => 'Economic',
                'name' => 'Labour productivity',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            33 => 
            array (
                'id' => 34,
                'module' => 'Performance',
                'domain' => 'Economic',
                'name' => 'Climate resilience',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            34 => 
            array (
                'id' => 35,
                'module' => 'Performance',
                'domain' => 'Social',
                'name' => 'Diet quality',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            35 => 
            array (
                'id' => 36,
                'module' => 'Performance',
                'domain' => 'Social',
                'name' => 'Farmer agency',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            36 => 
            array (
                'id' => 37,
                'module' => 'Performance',
                'domain' => 'Social',
                'name' => 'Human well-being',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
            37 => 
            array (
                'id' => 38,
                'module' => 'Performance',
                'domain' => 'Social',
                'name' => 'Land tenure',
                'created_at' => '2024-10-28 16:31:31',
                'updated_at' => '2024-10-28 16:31:31',
            ),
        ));
        
        
    }
}