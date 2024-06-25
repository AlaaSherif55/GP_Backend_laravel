<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hospital;
use App\Http\Resources\HospitalResource;

class HospitalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hospitals = Hospital::with('user')->get();
        return response()->json(HospitalResource::collection($hospitals));
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
    public function show(string $id)
    {
        $hospital = Hospital::with('user')->findOrFail($id);
        return response()->json(new HospitalResource($hospital));   
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $hospital = Hospital::findOrFail($id);
        $hospital->update($request->all());
        $hospital->user->update($request->all());
        return response()->json(["message" => "hospital updated successfully"],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function updateVerificationStatus(Request $request, Hospital $hospital){
        $status = $request['status'] ;
        $hospital->update(['verification_status' => $status]);
        return response()->json(["message" => "status updated successfully"],200);
    }
}
