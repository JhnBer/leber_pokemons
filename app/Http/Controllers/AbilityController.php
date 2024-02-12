<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAbilityRequest;
use App\Http\Requests\UpdateAbilityRequest;
use App\Models\Ability;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;

class AbilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $abilities = QueryBuilder::for(Ability::class)
            ->allowedFilters(['name', 'name_ru'])
            ->allowedSorts(['id', 'name', 'name_ru'])
            ->get();

        return response()->json($abilities, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAbilityRequest $request)
    {

        $validated = $request->validated();

        $file = $request->file('image');
        $filePath = $file->move(public_path('images'), $file->getClientOriginalName());
        $validated['image_url'] = $filePath;

        $ability = Ability::create($validated);

        return response()->json($ability, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ability $ability)
    {
        return response()->json($ability, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAbilityRequest $request, Ability $ability)
    {
        $validated = $request->validated();

        if($request->hasFile('image')){
            $file = $request->file('image');
            $filePath = $file->move(public_path('images'), $file->getClientOriginalName());
            $validated['image_url'] = $filePath;
        }

        $ability->update($validated);
        return response($ability, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ability $ability)
    {
        $ability->delete();
        return response()->noContent();
    }
}
