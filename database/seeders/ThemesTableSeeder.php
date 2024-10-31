<?php

namespace Database\Seeders;

use App\Models\Theme;
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
        Theme::create(['module' => 'Context', 'domain' => NULL, 'name' => 'Location']);
        Theme::create(['module' => 'Context', 'domain' => NULL, 'name' => 'Respondent characteristics']);
        Theme::create(['module' => 'Context', 'domain' => NULL, 'name' => 'Household characteristics']);
        Theme::create(['module' => 'Context', 'domain' => NULL, 'name' => 'Farm characteristics']);
        Theme::create(['module' => 'Context', 'domain' => NULL, 'name' => 'Production systems']);
        Theme::create(['module' => 'Context', 'domain' => NULL, 'name' => 'Climate change']);
        Theme::create(['module' => 'Context', 'domain' => NULL, 'name' => 'Movitation to transition']);

        Theme::create(['module' => 'Agroecology', 'domain' => NULL, 'name' => 'Recycling']);
        Theme::create(['module' => 'Agroecology', 'domain' => NULL, 'name' => 'Input reduction']);
        Theme::create(['module' => 'Agroecology', 'domain' => NULL, 'name' => 'Soil health']);
        Theme::create(['module' => 'Agroecology', 'domain' => NULL, 'name' => 'Animal health']);
        Theme::create(['module' => 'Agroecology', 'domain' => NULL, 'name' => 'Biodiversity']);
        Theme::create(['module' => 'Agroecology', 'domain' => NULL, 'name' => 'Synergies']);
        Theme::create(['module' => 'Agroecology', 'domain' => NULL, 'name' => 'Economic diversification']);
        Theme::create(['module' => 'Agroecology', 'domain' => NULL, 'name' => 'Knowledge co-creation']);
        Theme::create(['module' => 'Agroecology', 'domain' => NULL, 'name' => 'Governance']);
        Theme::create(['module' => 'Agroecology', 'domain' => NULL, 'name' => 'Social values and diets']);
        Theme::create(['module' => 'Agroecology', 'domain' => NULL, 'name' => 'Fairness']);
        Theme::create(['module' => 'Agroecology', 'domain' => NULL, 'name' => 'Connectivity']);
        Theme::create(['module' => 'Agroecology', 'domain' => NULL, 'name' => 'Participation']);

        Theme::create(['module' => 'Performance', 'domain' => 'Agricultural', 'name' => 'Crop health']);
        Theme::create(['module' => 'Performance', 'domain' => 'Agricultural', 'name' => 'Animal health']);
        Theme::create(['module' => 'Performance', 'domain' => 'Agricultural', 'name' => 'Soil health']);
        Theme::create(['module' => 'Performance', 'domain' => 'Agricultural', 'name' => 'Nutrient use ']);

        Theme::create(['module' => 'Performance', 'domain' => 'Environmental', 'name' => 'Biodiversity']);
        Theme::create(['module' => 'Performance', 'domain' => 'Environmental', 'name' => 'Agrobiodiversity']);
        Theme::create(['module' => 'Performance', 'domain' => 'Environmental', 'name' => 'Landscape complexity']);
        Theme::create(['module' => 'Performance', 'domain' => 'Environmental', 'name' => 'Climate mitigation']);
        Theme::create(['module' => 'Performance', 'domain' => 'Environmental', 'name' => 'Water']);
        Theme::create(['module' => 'Performance', 'domain' => 'Environmental', 'name' => 'Energy use']);

        Theme::create(['module' => 'Performance', 'domain' => 'Economic', 'name' => 'Income']);
        Theme::create(['module' => 'Performance', 'domain' => 'Economic', 'name' => 'Agricultural productivity']);
        Theme::create(['module' => 'Performance', 'domain' => 'Economic', 'name' => 'Labour productivity']);
        Theme::create(['module' => 'Performance', 'domain' => 'Economic', 'name' => 'Climate resilience']);

        Theme::create(['module' => 'Performance', 'domain' => 'Social', 'name' => 'Diet quality']);
        Theme::create(['module' => 'Performance', 'domain' => 'Social', 'name' => 'Farmer agency']);
        Theme::create(['module' => 'Performance', 'domain' => 'Social', 'name' => 'Human well-being']);
        Theme::create(['module' => 'Performance', 'domain' => 'Social', 'name' => 'Land tenure']);

    }
}
