<?php

namespace Database\Seeders\Prep;

use App\Models\Domain;
use Illuminate\Database\Seeder;

class DomainSeeder extends Seeder
{
    public function run()
    {
        Domain::create(['id' => 1, 'name' => 'Agricultural']);
        Domain::create(['id' => 2, 'name' => 'Environmental']);
        Domain::create(['id' => 3, 'name' => 'Economic']);
        Domain::create(['id' => 4, 'name' => 'Social']);
    }
}
