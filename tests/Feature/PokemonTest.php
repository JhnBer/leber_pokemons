<?php

namespace Tests\Feature;

use App\Enums\PokemonShapes;
use App\Models\Ability;
use App\Models\Location;
use App\Models\Pokemon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PokemonTest extends TestCase
{
    use WithFaker;
    // use RefreshDatabase;

    public function test_create_pokemon(): void
    {
        $abilities = [];
        for ($i=0; $i < rand(1, 2); $i++) { 
            $abilities[] = Ability::whereNot('id', $abilities)
                                ->inRandomOrder()
                                ->first()
                                ->id;
        }

        $data = [
            'name' => $this->faker->name(),
            'shape' => $this->faker->randomElement(PokemonShapes::array()),
            'location_id' => Location::inRandomOrder()->first()->id,
            'abilities' => $abilities,
            'image' => UploadedFile::fake()->image('pokemon.jpeg'),
        ];

        $response = $this->postJson(route('pokemons.store'), $data);
        $response->assertCreated();
    }

    public function test_create_pokemon_with_existing_name(): void
    {
        $pokemon = Pokemon::inRandomOrder()->first();

        $abilities = [];
        for ($i=0; $i < rand(1, 2); $i++) { 
            $abilities[] = Ability::whereNot('id', $abilities)
                                ->inRandomOrder()
                                ->first()
                                ->id;
        }

        $data = [
            'name' => $pokemon->name,
            'shape' => $this->faker->randomElement(PokemonShapes::array()),
            'location_id' => Location::inRandomOrder()->first()->id,
            'abilities' => $abilities,
            'image' => UploadedFile::fake()->image('pokemon.jpeg'),
        ];

        $response = $this->postJson(route('pokemons.store'), $data);
        $response->assertUnprocessable();
    }

    public function test_update_pokemon_name(): void
    {
        $pokemon = Pokemon::inRandomOrder()->first();

        $data = [
            'name' => $this->faker->name(),
        ];

        $response = $this->patchJson(route('pokemons.update', $pokemon->id), $data);
        $response->assertOk();
    }

    public function test_update_pokemon_shape(): void
    {
        $pokemon = Pokemon::inRandomOrder()->first();

        $data = [
            'shape' => $this->faker->randomElement(PokemonShapes::array()),
        ];

        $response = $this->patchJson(route('pokemons.update', $pokemon->id), $data);
        $response->assertOk();
    }

    public function test_update_pokemon_image(): void
    {
        $pokemon = Pokemon::inRandomOrder()->first();

        $data = [
            'image' => UploadedFile::fake()->image('pokemon.jpeg'),
        ];

        $response = $this->patchJson(route('pokemons.update', $pokemon->id), $data);
        $response->assertJsonFragment([
            'image_url' => $data['image'],
        ]);
        $response->assertOk();
    }
}
