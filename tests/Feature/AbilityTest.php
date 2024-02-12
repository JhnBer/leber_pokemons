<?php

namespace Tests\Feature;

use App\Models\Ability;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Faker\Factory as MyFaker;

class AbilityTest extends TestCase
{
    use WithFaker; 
    // use RefreshDatabase;

    protected $ruFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->ruFaker = MyFaker::create('ru_RU');
    }
    
    public function test_create_ability(): void
    {

        $data = [
            'name' => $this->faker->realText(mt_rand(10, 32)),
            'name_ru' => $this->ruFaker->realText(mt_rand(10, 32)),
            'image' => UploadedFile::fake()->image('ability.jpg'),
        ];

        $response = $this->postJson(route('ability.store'), $data);
        $response->assertCreated();
    }

    public function test_update_ability_name(): void
    {
        $data = [
            'name' => $this->faker->realText(mt_rand(10, 32)),
        ];
        
        $ability = Ability::inRandomOrder()->first();

        $response = $this->patchJson(route('ability.update', $ability->id), $data);
        $response->assertOk();
    }

    public function test_update_ability_name_ru(): void
    {
        $data = [
            'name_ru' => $this->ruFaker->realText(mt_rand(10, 32)),
        ];
        
        $ability = Ability::inRandomOrder()->first();

        $response = $this->patchJson(route('ability.update', $ability->id), $data);
        $response->assertOk();
    }

    public function test_update_ability_both_names(): void
    {
        $data = [
            'name' => $this->faker->realText(mt_rand(10, 32)),
            'name_ru' => $this->ruFaker->realText(mt_rand(10, 32)),
        ];

        $ability = Ability::inRandomOrder()->first();

        $response = $this->patchJson(route('ability.update', $ability->id), $data);
        $response->assertOk();
    }

    public function test_update_ability_image(): void
    {
        $data = [
            'image' => UploadedFile::fake()->image('ability-updated.jpg'),
        ];

        $ability = Ability::inRandomOrder()->first();

        $response = $this->patchJson(route('ability.update', $ability->id), $data);
        $response->assertOk();
    }

    public function test_get_all_abilities(): void
    {
        $response = $this->get(route('ability.index'));
        $response->assertOk();
    }

    public function test_destroy_ability(): void
    {
        $abilty = Ability::inRandomOrder()->first();

        $response = $this->deleteJson(route('ability.destroy', $abilty->id));
        $response->assertNoContent();
    }
}
