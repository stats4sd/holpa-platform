<?php

namespace Database\Seeders\Test;

use App\Models\Team;
use App\Models\LocalIndicator;
use App\Models\GlobalIndicator;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class IndicatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GlobalIndicator::factory()->count(50)->create();
    }
}
