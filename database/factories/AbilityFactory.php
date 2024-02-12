<?php

namespace Database\Factories;

use App\Models\Ability;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Faker\Factory as MyFaker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ability>
 */
class AbilityFactory extends Factory
{
    protected $model = Ability::class;
    protected $ruFaker;

    public function __construct($count = null)
    {
        parent::__construct();
        $this->count = $count;
        $this->ruFaker = MyFaker::create('ru_RU');
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' =>  $this->faker->realText(mt_rand(10, 32)),
            'name_ru' => $this->ruFaker->realText(mt_rand(10, 32)),
            'image_url' => UploadedFile::fake()->image('ability.jpg'),
        ];
    }
    
}
