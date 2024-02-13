<?php

namespace Database\Factories;

use App\Models\Ability;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as MyFaker;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ability>
 */
class AbilityFactory extends Factory
{
    protected $model = Ability::class;
    protected $ruFaker;

    public function __construct()
    {
        parent::__construct(...func_get_args());
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
            'image_url' => config('media.images.ability') . uniqid() . '.png',
        ];
    }

    public function withImages()
    {
        return $this->state(function (array $attributes){
            return [
                'name' => $attributes['name'],
                'name_ru' => $attributes['name_ru'],
                'image_url' => $attributes['image_url'],
            ];
        })->afterCreating(function (Ability $ability) {
            $image = file_get_contents('https://via.placeholder.com/150?' . http_build_query(['text' => $ability->name]));
            File::ensureDirectoryExists(public_path(config('media.images.ability')));  
            File::put(public_path($ability->image_url), $image);
        });
    }
    
}
