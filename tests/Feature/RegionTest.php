<?php

namespace Tests\Feature;

use App\Enums\Regions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Region;

class RegionTest extends TestCase
{
    use WithFaker;
    // use RefreshDatabase;

    public function test_create_region(): void
    {
        $data = [
            'name' => $this->faker->randomElement(Regions::array()),
        ];


        if(Region::where('name', $data['name'])->doesntExist()){
            $waitForSuccess = true;
        }else{
            $waitForSuccess = false;
        }
        
        $response = $this->postJson(route('region.store'), $data);

        if($waitForSuccess){
            $response->assertCreated();
        }else{
            $response->assertBadRequest();
        }
    }

    public function test_update_region(): void
    {
        $data = [
            'name' => $this->faker->randomElement(Regions::array()),
        ];

        $region = Region::inRandomOrder()->first();

        if(Region::where('name', $data['name'])->doesntExist()){
            $waitForSuccess = true;
        }else{
            $waitForSuccess = false;
        }

        $response = $this->patchJson(route('region.update', $region->id), $data);

        if($waitForSuccess){
            $response->assertOk();
        }else{
            $response->assertBadRequest();
        }
    }

    public function test_get_all_regions(): void
    {
        $response = $this->get(route('region.index'));
        $response->assertOk();
    }

    public function test_show_region(): void
    {
        $region = Region::inRandomOrder()->first();

        $response = $this->get(route('region.show', $region->id));

        $response->assertJson([
            'id' => $region->id,
            'name' => $region->name,
        ]);
    }

    public function test_destroy_region(): void
    {
        $region = Region::inRandomOrder()->first();

        $response = $this->deleteJson(route('region.destroy', $region->id));

        $response->assertNoContent();
    }
    
}
