<?php

use App\Enums\PokemonShapes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pokemons', function (Blueprint $table) {
            $table->id();

            $table->string('name', 32)->unique();
            $table->enum('shape', PokemonShapes::array());
            $table->integer('order')->nullable();
            $table->string('image_url', 64);
            $table->integer('location_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pokemons');
    }
};
