<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Nurse;
use Illuminate\Http\Request;

use App\Http\Resources\NurseResource;

class NurseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Nurse $nurse)
    {
        $nurse = Nurse::with('user')->findOrFail($nurse->id);
        return response()->json(["status" => "success", 
        "data" => new NurseResource($nurse)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Nurse $nurse)
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
