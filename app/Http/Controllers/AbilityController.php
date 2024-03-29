<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAbilityRequest;
use App\Http\Requests\UpdateAbilityRequest;
use App\Http\Resources\AbilityResource;
use App\Models\Ability;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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

        return response()->json(AbilityResource::collection($abilities), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAbilityRequest $request)
    {
        $validated = $request->validated();

        $file = $request->file('image');
        $fileName = uniqid().'.'.$file->getClientOriginalExtension();
        $filePath = config('media.images.ability');

        $file->move(public_path($filePath), $fileName);
        $validated['image_url'] = $filePath . $fileName;

        $ability = Ability::create($validated);

        return response()->json($ability, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ability $ability)
    {
        return response()->json(new AbilityResource($ability), Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAbilityRequest $request, Ability $ability)
    {
        $validated = $request->validated();

        if($request->hasFile('image')){
            $file = $request->file('image');
            $fileName = uniqid().'.'.$file->getClientOriginalExtension();
            $filePath = config('media.images.ability');

            $file->move(public_path($filePath), $fileName);
            $validated['image_url'] = $filePath . $fileName;
            File::delete(public_path($ability->image_url));
        }

        $ability->update($validated);
        return response(new AbilityResource($ability), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ability $ability)
    {
        $ability->delete();
        return response()->noContent();
    }

    /**
     * 
     *  */ 
    public function getImage(Ability $ability)
    {
        if(!File::exists($ability->image_url)){
            return response()->json(['message' => 'Image not found'], Response::HTTP_NOT_FOUND);
        }

        return response(
                file_get_contents($ability->image_url), 
                Response::HTTP_OK, 
                [
                    'Content-Type' => File::type($ability->image_url),
                ],       
        );
    }
}
