<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipmentRequest;
use App\Http\Requests\UpdateEquipmentRequest;
use App\Models\Equipment;

class EquipmentController extends Controller
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
    public function store(StoreEquipmentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipment $equipment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEquipmentRequest $request, Equipment $equipment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipment $equipment)
    {
        //
    }
}
