<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\PokemonShapes;
use App\Models\Ability;
use App\Models\Location;
use App\Models\Pokemon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pokemon>
 */
class PokemonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'shape' => $this->faker->randomElement(PokemonShapes::array()),
            'location_id' => Location::inRandomOrder()->first()->id,
            'image_url' => UploadedFile::fake()->image('pokemon.jpeg'),
        ];
    }

    public function withAbilities()
    {
        return $this->state(function (array $attributes){
            return [
                'name' => $attributes['name'],
                'shape' => $attributes['shape'],
                'location_id' => $attributes['location_id'],
                'image_url' => $attributes['image_url'],
            ];
        })->afterCreating(function (Pokemon $pokemon) {
            $abilities = [];
            for ($i=0; $i < rand(1, 2); $i++) { 
                do {
                    $ability = Ability::inRandomOrder()->first();
                } while (
                    DB::table('pokemon_ability')->where('pokemon_id', $pokemon->id)->where('ability_id', $ability->id)->exists() ||
                    in_array($ability->id, $abilities)
                );
                $abilities[] = $ability->id;
            }

            $pokemon->abilities()->attach($abilities);
        });
    }

    public function withOrder()
    {
        return $this->state(function (array $attributes){
            return [
                'name' => $attributes['name'],
                'shape' => $attributes['shape'],
                'location_id' => $attributes['location_id'],
                'image_url' => $attributes['image_url'],
            ];
        })->afterCreating(function (Pokemon $pokemon) {
            $pokemon->order = $pokemon->id;
            $pokemon->save();
        });
    }
}
