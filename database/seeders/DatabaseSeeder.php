<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Ability;
use App\Models\Region;
use App\Enums\Regions;
use App\Models\Location;
use App\Models\Pokemon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Ability::factory(10)->create();

        foreach(Regions::values() as $region){
            Region::create([
                'name' => $region
            ]);
        }

        Location::factory(10)->create();
        Location::factory(5)->withParent()->create(); 
        Location::factory(5)->withParent()->create();

        Pokemon::factory(10)->withAbilities()->create();
    }
}
