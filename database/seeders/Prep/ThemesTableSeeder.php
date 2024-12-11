<?php

namespace Database\Seeders\Prep;

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
        Theme::create(['module' => 'Context', 'domain_id' => NULL, 'name' => 'Location']);
        Theme::create(['module' => 'Context', 'domain_id' => NULL, 'name' => 'Respondent characteristics']);
        Theme::create(['module' => 'Context', 'domain_id' => NULL, 'name' => 'Household characteristics']);
        Theme::create(['module' => 'Context', 'domain_id' => NULL, 'name' => 'Farm characteristics']);
        Theme::create(['module' => 'Context', 'domain_id' => NULL, 'name' => 'Production systems']);
        Theme::create(['module' => 'Context', 'domain_id' => NULL, 'name' => 'Climate change']);
        Theme::create(['module' => 'Context', 'domain_id' => NULL, 'name' => 'Movitation to transition']);

        Theme::create(['module' => 'Agroecology', 'domain_id' => NULL, 'name' => 'Recycling']);
        Theme::create(['module' => 'Agroecology', 'domain_id' => NULL, 'name' => 'Input reduction']);
        Theme::create(['module' => 'Agroecology', 'domain_id' => NULL, 'name' => 'Soil health']);
        Theme::create(['module' => 'Agroecology', 'domain_id' => NULL, 'name' => 'Animal health']);
        Theme::create(['module' => 'Agroecology', 'domain_id' => NULL, 'name' => 'Biodiversity']);
        Theme::create(['module' => 'Agroecology', 'domain_id' => NULL, 'name' => 'Synergies']);
        Theme::create(['module' => 'Agroecology', 'domain_id' => NULL, 'name' => 'Economic diversification']);
        Theme::create(['module' => 'Agroecology', 'domain_id' => NULL, 'name' => 'Knowledge co-creation']);
        Theme::create(['module' => 'Agroecology', 'domain_id' => NULL, 'name' => 'Governance']);
        Theme::create(['module' => 'Agroecology', 'domain_id' => NULL, 'name' => 'Social values and diets']);
        Theme::create(['module' => 'Agroecology', 'domain_id' => NULL, 'name' => 'Fairness']);
        Theme::create(['module' => 'Agroecology', 'domain_id' => NULL, 'name' => 'Connectivity']);
        Theme::create(['module' => 'Agroecology', 'domain_id' => NULL, 'name' => 'Participation']);

        Theme::create(['module' => 'Performance', 'domain_id' => 1, 'name' => 'Crop health']);
        Theme::create(['module' => 'Performance', 'domain_id' => 1, 'name' => 'Animal health']);
        Theme::create(['module' => 'Performance', 'domain_id' => 1, 'name' => 'Soil health']);
        Theme::create(['module' => 'Performance', 'domain_id' => 1, 'name' => 'Nutrient use ']);

        Theme::create(['module' => 'Performance', 'domain_id' => 2, 'name' => 'Biodiversity']);
        Theme::create(['module' => 'Performance', 'domain_id' => 2, 'name' => 'Agrobiodiversity']);
        Theme::create(['module' => 'Performance', 'domain_id' => 2, 'name' => 'Landscape complexity']);
        Theme::create(['module' => 'Performance', 'domain_id' => 2, 'name' => 'Climate mitigation']);
        Theme::create(['module' => 'Performance', 'domain_id' => 2, 'name' => 'Water']);
        Theme::create(['module' => 'Performance', 'domain_id' => 2, 'name' => 'Energy use']);

        Theme::create(['module' => 'Performance', 'domain_id' => 3, 'name' => 'Income']);
        Theme::create(['module' => 'Performance', 'domain_id' => 3, 'name' => 'Agricultural productivity']);
        Theme::create(['module' => 'Performance', 'domain_id' => 3, 'name' => 'Labour productivity']);
        Theme::create(['module' => 'Performance', 'domain_id' => 3, 'name' => 'Climate resilience']);

        Theme::create(['module' => 'Performance', 'domain_id' => 4, 'name' => 'Diet quality']);
        Theme::create(['module' => 'Performance', 'domain_id' => 4, 'name' => 'Farmer agency']);
        Theme::create(['module' => 'Performance', 'domain_id' => 4, 'name' => 'Human well-being']);
        Theme::create(['module' => 'Performance', 'domain_id' => 4, 'name' => 'Land tenure']);

    }
}
