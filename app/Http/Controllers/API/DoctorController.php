<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\DB;


use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorAppointment;
use App\Models\Prescriptions;

use App\Http\Controllers\API\AuthController;


use Illuminate\Http\Request;

use App\Http\Resources\DoctorResource;
use App\Http\Resources\DoctorAppointmentsResource;
use App\Http\Resources\PrescriptionsResource;


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
            if (!empty($request['image'])) {
                $doctor->image = app('App\Http\Controllers\API\AuthController')->uploadFileToCloudinary($request, 'image');
                $doctor->save();
            }
           
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
    // public function getDoctorAppointments( string $doctor_id ){
    //     // $perPage = request()->query('perPage', 7);
    //     // $appointments = DoctorAppointment::where('doctor_id', $doctor_id)->get();
    //     $appointments = DoctorAppointment::with(['patient.user'])
    //     ->where('doctor_id', $doctor_id)
    //     ->get();
    //     return response()->json(["status" => "success", "data" => DoctorAppointmentsResource::collection($appointments)]);

    // }
    public function getDoctorAppointments(string $doctor_id){
        $kindOfVisit = request()->query('kind_of_visit');
        $date = request()->query('date');

        $query = DoctorAppointment::with(['patient.user'])
            ->where('doctor_id', $doctor_id);

        if ($kindOfVisit !== "all") {
            $query->where('kind_of_visit', $kindOfVisit);
        }

        if ($date) {
            $query->whereDate('date', $date);
        }

        $appointments = $query->get();

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
    public function getDoctorPrescriptions( string $doctor_id ){
        $prescriptions = Prescriptions::with(['patient.user'])
        ->where('doctor_id', $doctor_id)
        ->get();
        // return $prescriptions ;
        return response()->json(["status" => "success", "data" => PrescriptionsResource::collection($prescriptions)]);

    }
    public function getReadPrescriptions(string $doctor_id) {
        $prescriptions = Prescriptions::with(['patient.user'])
            ->where('doctor_id', $doctor_id)
            ->where('read', 1)
            ->get();
    
        return response()->json(["status" => "success", "data" => PrescriptionsResource::collection($prescriptions)]);
    }
    public function getUnreadPrescriptions(string $doctor_id) {
        $prescriptions = Prescriptions::with(['patient.user'])
            ->where('doctor_id', $doctor_id)
            ->where('read', 0)
            ->get();
    
        return response()->json(["status" => "success", "data" => PrescriptionsResource::collection($prescriptions)]);
    }
    
    public function ReplyToDoctorPrescription( Request $request,string $prescription_id ){
        $prescription = Prescriptions::find($prescription_id);
        
        if ($prescription) {
            $prescription->update(['description' =>$request['description'] , 'read' => 1]);
            return response()->json(["message" => "your description added successfully"],200);
        } else {
            return response()->json(["message" => "prescription not found"], 404);
        }
    }
    public function VerifyDoctor( Request $request,string $doctor_id ){
        $doctor = Doctor::find($doctor_id);
        if ($doctor) {
            $doctor->update(['verification_status' =>$request['verification_status']]);
            return response()->json(["message" => "Doctor Verified successfully"],200);
        } else {
            return response()->json(["message" => "Doctor not found"], 404);
        }
    }


}
