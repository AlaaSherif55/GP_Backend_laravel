<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePrescriptionsRequest;
use App\Http\Requests\UpdatePrescriptionsRequest;
use App\Models\Prescriptions;

class PrescriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePrescriptionsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Prescriptions $prescriptions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prescriptions $prescriptions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePrescriptionsRequest $request, Prescriptions $prescriptions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prescriptions $prescriptions)
    {
        //
    }
}
