<?php

namespace Tests\Feature;

use App\Models\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Location;
use App\Models\Pokemon;

class LocationTest extends TestCase
{
    use WithFaker;
    // use RefreshDatabase;

    public function test_create_location(): void
    {
        $data = [
            'name' => $this->faker->realText(mt_rand(10, 32)),
            'region_id' => Region::inRandomOrder()->first()->id,
        ];

        $response = $this->postJson(route('location.store'), $data);

        $response->assertCreated();
    }

    public function test_create_location_with_parent(): void
    {
        $data = [
            'name' => $this->faker->realText(mt_rand(10, 32)),
            'parent_id' => Location::inRandomOrder()->first()->id,
        ];

        $response = $this->postJson(route('location.store'), $data);
        $response->assertCreated();
    }

    public function test_update_location_name(): void
    {
        $data = [
            'name' => $this->faker->realText(mt_rand(10, 32)),
        ];

        $location = Location::inRandomOrder()->first();

        $response = $this->patchJson(route('location.update', $location->id), $data);
        $response->assertOk();
    }

    public function test_update_parent_on_location_with_parent(): void
    {
        $location = Location::whereNot('parent_id', null)->inRandomOrder()->first();
        $locationParent = Location::whereNot('id', $location->id)->inRandomOrder()->first();

        $data = [
            'parent_id' => $locationParent->id,
        ];

        $response = $this->patchJson(route('location.update', $location->id), $data);

        $response->assertJsonFragment([
            'region_id' => $locationParent->region_id,
            'parent_id' => $locationParent->id
        ]);
        $response->assertOk();
    }

    public function test_destroy_non_parent_location(): void
    {
        $location = Location::whereNotIn('id', function ($query) {
                $query->select('parent_id')
                    ->from('locations')
                    ->whereNotNull('parent_id');
            })
            ->inRandomOrder()
            ->first();

        $usedAsPokemonLocation = Pokemon::where('location_id', $location->id)->exists();

        $response = $this->deleteJson(route('location.destroy', $location->id));

        if($usedAsPokemonLocation){
            $response->assertUnprocessable();
        }else{
            $response->assertNoContent();
        }
    }

    public function test_destroy_parent_location(): void
    {
        $location = Location::whereIn('id', function ($query) {
                $query->select('parent_id')
                    ->from('locations');
            })
            ->inRandomOrder()
            ->first();

        $response = $this->deleteJson(route('location.destroy', $location->id));
        $response->assertUnprocessable();
    }

    public function test_destroy_location_used_in_pokemons(): void
    {
        $location = Location::whereIn('id', function ($query) {
                $query->select('location_id')
                    ->from('pokemons')
                    ->whereNotNull('location_id');
        })
            ->inRandomOrder()
            ->first(); 

        $response = $this->deleteJson(route('location.destroy', $location->id));
        $response->assertUnprocessable();
    }
}
