<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAbilityRequest;
use App\Http\Requests\UpdateAbilityRequest;
use App\Models\Ability;
use Illuminate\Http\Request;

class AbilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAbilityRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Ability $ability)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAbilityRequest $request, Ability $ability)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ability $ability)
    {
        //
    }
}
