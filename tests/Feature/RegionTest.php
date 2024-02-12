<?php

namespace Tests\Feature;

use App\Enums\Regions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Region;
use App\Models\Location;

class RegionTest extends TestCase
{
    use WithFaker;
    // use RefreshDatabase;

    public function test_create_new_region(): void
    {
        $regions = Regions::array();
        $data = [
            'name' => $this->faker->randomElement($regions),
        ];
        
        $nameExists = Region::where('name', $data['name'])->exists();
        if($nameExists){
            foreach ($regions as $region) {
                if(Region::where('name', $region)->doesntExist()){
                    $data['name'] = $region;
                    $nameExists = false;
                    break;
                }
            }
        }

        $regionsCount = Region::all()->count();

        $response = $this->postJson(route('region.store'), $data);

        if($regionsCount == count(Regions::array())){
            $response->assertUnprocessable();
        }else{
            $response->assertOk();
        }
    }

    public function test_create_existing_region(): void
    {
        $newName = Region::inRandomOrder()->first()->name;
        
        $data = [
            'name' => $newName,
        ];

        $response = $this->postJson(route('region.store'), $data);
        $response->assertUnprocessable();
    }

    public function test_update_region_name_to_new(): void
    {
        $data = [
            'name' => $this->faker->randomElement(Regions::array()),
        ];

        $nameExists = Region::where('name', $data['name'])->exists();
        foreach (Regions::array() as $region) {
            if(Region::where('name', $region)->doesntExist()){
                $data['name'] = $region;
                $nameExists = false;
            }
        }

        $region = Region::inRandomOrder()->first();

        $regionsCount = Region::all()->count();

        $response = $this->patchJson(route('region.update', $region->id), $data);

        if($regionsCount == count(Regions::array())){
            $response->assertUnprocessable();
        }else{
            $response->assertOk();
        }
        

    } 

    public function test_update_region_name_to_existing(): void
    {
        $newName = Region::inRandomOrder()->first()->name;
        
        $data = [
            'name' => $newName,
        ];

        $region = Region::inRandomOrder()->first();

        $response = $this->patchJson(route('region.update', $region->id), $data);
        $response->assertUnprocessable();
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

        if(Location::where('region_id', $region->id)->exists()){
            $response->assertUnprocessable();
        }else{
            $response->assertNoContent();
        }
    }
    
}
