<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Pokemon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image_url',
        'order',
        'shape',
        'location_id',
    ];

    protected $table = 'pokemons';

    public function region(): HasOneThrough
    {
        return $this->hasOneThrough(Region::class, Location::class, 'id', 'id', 'location_id', 'region_id');
    }

    public function abilities(): BelongsToMany
    {
        return $this->belongsToMany(Ability::class, 'pokemon_ability');
    }
}
