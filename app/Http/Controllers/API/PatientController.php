<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDoctorAppointmentRequest;
use App\Http\Requests\StoreNurseAppointmentRequest;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\DoctorAppointment;
use App\Models\Patient;
use Exception;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $patients = Patient::all();
            return response()->json($patients);
        }
        catch(Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePatientRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        try {
            $patient = Patient::findOrFail($patient->id);
            return response()->json($patient);
        }
        catch(Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        try {
            $destroyed = Patient::findOrFail($patient->id);
            $destroyed->destroy();

            return response()->json($destroyed);
        }
        catch(Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Reserve a doctor.
     */
    public function reserveDoctor(StoreDoctorAppointmentRequest $request)
    {
        try {
            $validated = $request->validated();

            $appointment = new DoctorAppointment();
            $appointment->patient_id = $validated['patient_id'];
            $appointment->doctor_id = $validated['doctor_id'];
            $appointment->kind_of_visit = $validated['kind_of_visit'];
            $appointment->day = $validated['day'];

            $appointment->save();

            return response()->json($appointment);
        }
        catch(Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Reserve a nurse.
     */
    public function reserveNurse(StoreNurseAppointmentRequest $request)
    {
        try {
            $validated = $request->validated();

            $appointment = new DoctorAppointment();
            $appointment->patient_id = $validated['patient_id'];
            $appointment->doctor_id = $validated['doctor_id'];
            $appointment->day = $validated['day'];

            $appointment->save();

            return response()->json($appointment);
        }
        catch(Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function getAllAppointments(Patient $patient)
    {
        $appointments = $patient->appointments; // Assuming you have a relationship defined in the Patient model
        return response()->json($appointments);
    }

    // Method to get doctor appointments for a patient
    public function getDoctorAppointments(Patient $patient)
    {
        try {
            $appointments = $patient->doctor_appointments;
            return response()->json($appointments);
        }
        catch(Exception $e) {
            return response()->json($e->getMessage());
        } 
    }

    // Method to get nurse appointments for a patient
    public function getNurseAppointments(Patient $patient)
    {
        try {
            $appointments = $patient->nurse_appointments;
            return response()->json($appointments);
        }
        catch(Exception $e) {
            return response()->json($e->getMessage());
        } 
    }
    public function payment() {
        
    }
}
