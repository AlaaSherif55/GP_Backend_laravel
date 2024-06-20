<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\DB;


use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorAppointment;

use Illuminate\Http\Request;

use App\Http\Resources\DoctorResource;
use App\Http\Resources\DoctorAppointmentsResource;


class DoctorController extends Controller
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        $doctor = Doctor::with('user')->findOrFail($doctor->id);
        return response()->json(["status" => "success", 
        "data" => new DoctorResource($doctor)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        try {
            DB::beginTransaction();
    
            $user = $doctor->user;
            $user->update($request->all());
    
            $doctor->update($request->all());
          
            DB::commit();
            $doctor->refresh();
            return response()->json([
                "status" => "success",
                "data" => new DoctorResource($doctor)
                
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
    public function destroy(Doctor $doctor)
    {
        //
    }
    public function getDoctorAppointments( string $doctor_id ){
        // $perPage = request()->query('perPage', 7);
        $doctor = new DoctorResource(Doctor::find($doctor_id));
        // $appointments = DoctorAppointment::where('doctor_id', $doctor_id)->get();
        $appointments = DoctorAppointment::with(['patient.user'])
        ->where('doctor_id', $doctor_id)
        ->get();
        return response()->json(["status" => "success", "data" => DoctorAppointmentsResource::collection($appointments)]);

    }
    public function ApproveDoctorAppointments( Request $request,string $appointment_id ){
        $appointment = DoctorAppointment::find($appointment_id);
        
        if ($appointment) {
            $appointment->update(['status' =>$request['status']]);
            return response()->json(["message" => "Appointment approved successfully"],200);
        } else {
            return response()->json(["message" => "Appointment not found"], 404);
        }
    }
    public function AddNoteToDoctorAppointments( Request $request,string $appointment_id ){
        $appointment = DoctorAppointment::find($appointment_id);
        
        if ($appointment) {
            $appointment->update(['notes' =>$request['notes']]);
            return response()->json(["message" => "Notes added successfully"],200);
        } else {
            return response()->json(["message" => "Appointment not found"], 404);
        }
    }
}