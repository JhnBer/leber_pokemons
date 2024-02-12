<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePokemonRequest;
use App\Http\Requests\UpdatePokemonRequest;
use App\Models\Pokemon;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\File;

class PokemonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pokemons = QueryBuilder::for(Pokemon::class)
            ->with(['abilities'])
            ->get();
        return response()->json($pokemons, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePokemonRequest $request)
    {
        $validated = $request->validated();

        $file = $request->file('image');
        $filePath = $file->move(public_path('images/pokemon/'), $file->getClientOriginalName());
        $validated['image_url'] = $filePath;

        $pokemon = Pokemon::create($validated);

        foreach ($validated['abilities'] as $abilityId) {
            $pokemon->abilities()->attach($abilityId);
        }

        $pokemon->order = $pokemon->id;
        $pokemon->save();

        return response()->json($pokemon, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pokemon $pokemon)
    {
        return response()->json($pokemon, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePokemonRequest $request, Pokemon $pokemon)
    {
        $validated = $request->validated();

        if($request->hasFile('image')){
            $file = $request->file('image');
            $filePath = $file->move(public_path('images/pokemon/'), uniqid().'.'.$file->getClientOriginalExtension());
            $validated['image_url'] = $filePath;
            File::delete($pokemon->image_url);
        }

        if(isset($validated['abilities'])){
            $pokemon->abilities()->detach();
            $pokemon->abilities()->attach($validated['abilities']);
        }

        $pokemon->update($validated);
        return response()->json($pokemon, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pokemon $pokemon)
    {
        $pokemon->delete();
        return response()->noContent();
    }
}
