<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IntensiveCareApplication;
use App\Http\Requests\StoreIntensiveCareApplicationRequest;
use App\Http\Resources\IntensiveCareApplicationResource;
class IntensiveCareApplicationController extends Controller
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
    public function store(StoreIntensiveCareApplicationRequest $request)
    {
        $application = $request->validated();
        $application['status'] = 'pending';
        IntensiveCareApplication::create($application);    
        return response()->json('Intensive Care Application created successfully');
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $application = IntensiveCareApplication::with('intensiveCareUnit.hospital.user', 'intensiveCareUnit.equipments')->find($id);
        if (!$application) {
            return response()->json(['message' => 'Application not found'], 404);
        }
        return new IntensiveCareApplicationResource($application);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
