<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNurseRequest;
use App\Http\Requests\UpdateNurseRequest;
use App\Models\Nurse;

class NurseController extends Controller
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
    public function store(StoreNurseRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Nurse $nurse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNurseRequest $request, Nurse $nurse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Nurse $nurse)
    {
        //
    }
}
