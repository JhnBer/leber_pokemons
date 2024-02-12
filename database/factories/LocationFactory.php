<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    protected $model = Location::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->realText(mt_rand(10, 32)),
            'region_id' => Region::inRandomOrder()->first()->id,
        ];
    }

    public function withParent()
    {
        return $this->state(function (array $attributes){
            $parentLocation = Location::inRandomOrder()->first();
            return [
                'name' => $attributes['name'],
                'parent_id' => $parentLocation->id,
                'region_id' => $parentLocation->region_id,
            ];
        });
    }
}
