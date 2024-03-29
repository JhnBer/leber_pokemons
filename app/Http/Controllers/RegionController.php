<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegionRequest;
use App\Http\Requests\UpdateRegionRequest;
use App\Http\Resources\RegionResource;
use App\Models\Region;
use App\Models\Location;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\QueryBuilder\QueryBuilder;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $regions = QueryBuilder::for(Region::class)
            ->allowedFilters(['name'])
            ->allowedSorts(['id', 'name'])
            ->get();

        return response()->json(RegionResource::collection($regions), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRegionRequest $request)
    {
        if(Region::where('name', $request->name)->exists()){
            return response()->json(['message' => 'Already exists'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $region = Region::create($request->validated());
        return response()->json($region, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region)
    {
        return response()->json($region, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegionRequest $request, Region $region)
    {
        if(Region::where('name', $request->name)->exists()){
            return response()->json(['message' => 'Already exists'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $region->update($request->validated());
        return response()->json(new RegionResource($region), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region)
    {
        if(Location::where('region_id', $region->id)->exists()){
            return response()->json(['message' => 'Linked locations found'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $region->delete();
        return response()->noContent();
    }
}
