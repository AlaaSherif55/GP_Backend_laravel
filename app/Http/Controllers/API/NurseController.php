<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\Nurse;
use App\Models\NurseAppointment;


use Illuminate\Http\Request;

use App\Http\Resources\NurseResource;
use App\Http\Resources\NurseAppointmentsResource;


class NurseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $nurses = Nurse::with('user')->get();
        
        return response()->json([
            "status" => "success",
            "data" => NurseResource::collection($nurses)
        ]);
    }

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
            if (!empty($request['image'])) {
                $nurse->image = app('App\Http\Controllers\API\AuthController')->uploadFileToCloudinary($request, 'image');
                $nurse->save();
            }
           
          
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
    public function getNurseAppointments( string $nurse_id ){
        // $appointments = NurseAppointment::with(['patient.user'])
        $appointments = NurseAppointment::with("patient")
        ->where('nurse_id',$nurse_id)
        ->get();
        return response()->json(["status" => "success", "data" => NurseAppointmentsResource::collection($appointments)]);

    }
    public function ApproveNurseAppointments( Request $request,string $appointment_id ){
        $appointment = NurseAppointment::find($appointment_id);
        if ($appointment) {
            $appointment->update(['status' =>$request['status']]);
            return response()->json(["message" => "Appointment approved successfully"],200);
        } else {
            return response()->json(["message" => "Appointment not found"], 404);
        }
    }
    public function AddNoteToNurseAppointments( Request $request,string $appointment_id ){
        $appointment = NurseAppointment::find($appointment_id);
        
        if ($appointment) {
            $appointment->update(['notes' =>$request['notes']]);
            return response()->json(["message" => "Notes added successfully"],200);
        } else {
            return response()->json(["message" => "Appointment not found"], 404);
        }
    }
    public function VerifyNurse( Request $request,string $nurse_id ){
        $nurse = Nurse::find($nurse_id);
        if ($nurse) {
            $nurse->update(['verification_status' =>$request['verification_status']]);
            return response()->json(["message" => "Nurse Verified successfully"],200);
        } else {
            return response()->json(["message" => "Nurse not found"], 404);
        }
    }

}
