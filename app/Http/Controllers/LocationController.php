<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Models\Location;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;


class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $regions = QueryBuilder::for(Location::class)
            ->with('region')
            ->join('regions', 'regions.id', '=', 'locations.region_id')
            ->select('locations.name as location_name')
            ->select('locations.*')
            ->allowedFilters(['region.name'])
            ->allowedSorts([
                'name', 
                AllowedSort::field('region', 'regions.name')
                ])
            ->get();

        return response()->json($regions, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLocationRequest $request)
    {
        $validated = $request->validated();
        if(isset($validated['parent_id'])){
            $parentLocation = Location::where('id', $validated['parent_id'])->first();
            $validated['region_id'] = $parentLocation->region_id;
        }
        $location = Location::create($validated);
        return response($location, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        return response()->json($location, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLocationRequest $request, Location $location)
    {
        $validated = $request->validated();
        if(isset($validated['parent_id'])){
            $parentLocation = Location::where('id', $validated['parent_id'])->first();
            if($parentLocation->region_id !== $location->region_id){
                $validated['region_id'] = $parentLocation->region_id;
            }
        }
        $location->update($validated);
        return response()->json($location, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        if(Location::where('parent_id', $location->id)->exists()){
            return response()->json(['message' => 'This location used as parent location'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        $location->delete();
        return response()->noContent();
    }
}
