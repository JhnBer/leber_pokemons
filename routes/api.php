<?php

use App\Http\Controllers\AbilityController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PokemonController;
use App\Http\Controllers\RegionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('abilities')->name('abilities.')->group(function () {
    Route::apiResource('', AbilityController::class)->parameters([
        '' => 'ability'
    ]);
    Route::get('{ability}/image', [AbilityController::class, 'getImage'])->name('image');
});

Route::apiResource('regions', RegionController::class);

Route::apiResource('locations', LocationController::class);

Route::prefix('pokemons')->name('pokemons.')->group(function () {
    Route::apiResource('', PokemonController::class)->parameters([
        '' => 'pokemon'
    ]);
    Route::get('{pokemon}/image', [PokemonController::class, 'getImage'])->name('image');
});
