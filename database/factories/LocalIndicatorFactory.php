<?php

namespace Database\Factories;

use App\Models\Theme;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GlobalIndicator>
 */
class LocalIndicatorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence,
            'team_id' => null,
            'theme_id' => $this->faker->randomElement(Theme::all()->pluck('id')->toArray()),
            'global_indicator_id' => null,
        ];
    }
}
