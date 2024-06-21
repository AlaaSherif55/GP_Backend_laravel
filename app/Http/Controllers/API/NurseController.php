<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\DB;

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
        try {
            DB::beginTransaction();
    
            $user = $nurse->user;
            $user->update($request->all());
    
            $nurse->update($request->all());
          
            DB::commit();
            $nurse->refresh();
            return response()->json([
                "status" => "success",
                "data" => new NurseResource($nurse)
                
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json([
                "status" => "error",
                "message" => "Failed to update user and doctor",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Nurse $nurse)
    {
        //
    }
}
