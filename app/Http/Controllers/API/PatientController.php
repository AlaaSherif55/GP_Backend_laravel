<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDoctorAppointmentRequest;
use App\Http\Requests\StoreNurseAppointmentRequest;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\StorePrescriptionsRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Http\Resources\DoctorAppointmentsResource;
use App\Http\Resources\NurseAppointmentsResource;
use App\Http\Resources\PatientResource;
use App\Http\Resources\PrescriptionsResource;
use App\Models\Doctor;
use App\Models\DoctorAppointment;
use App\Models\DoctorRating;
use App\Models\NurseAppointment;
use App\Models\NurseRating;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Prescriptions;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $patients = Patient::all();
            return response()->json(PatientResource::collection($patients));
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
            return response()->json(PatientResource::make($patient));
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
        try {
            DB::beginTransaction();
            $updatedPatient = Patient::findOrFail($patient->id);
            $updatedUser = User::findOrFail($updatedPatient->user->id);

            $updatedPatient->update($request->all());
            $updatedUser->update($request->all());

            $updatedPatient->save();
            $updatedUser->save();
            
            $updatedPatient->refresh();
            DB::commit();
            return response()->json(PatientResource::make($updatedPatient));
        }
        catch(Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage());
        }
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
    public function reviewDoctor(Request $request, Patient $patient)
    {
        try {
            $data = $request->all();
            DB::beginTransaction();
            $doctorRating = new DoctorRating();
            $doctorRating->rating = $data['rating'];
            $doctorRating->review = $data['review'];
            $doctorRating->patient_id = $patient->id;
            $doctorRating->doctor_id = $data['medic_id'];

            $appointment = DoctorAppointment::findOrFail($data['appointment_id']);
            $appointment->is_reviewed = true;

            $doctorRating->save();
            $appointment->save();
            DB::commit();
            return response()->json();
        }
        catch(Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage());
        }
    }

    /**
     * Reserve a nurse.
     */
    public function reviewNurse(Request $request, Patient $patient)
    {
        try {
            $data = $request->all();
            DB::beginTransaction();
            $nurseRating = new NurseRating();
            $nurseRating->rating = $data['rating'];
            $nurseRating->review = $data['review'];
            $nurseRating->patient_id = $patient->id;
            $nurseRating->nurse_id = $data['medic_id'];

            $appointment = NurseAppointment::findOrFail($data['appointment_id']);
            $appointment->is_reviewed = true;

            $nurseRating->save();
            $appointment->save();
            DB::commit();
            return response()->json();
        }
        catch(Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage());
        }
    }

    public function getAllAppointments(Patient $patient, Request $request)
    {
        try {

            $patient = Patient::findOrFail($patient->id);
            $query = $request->all();
            
            $appointments = collect();
            $totalLength = 0;
    
            if (isset($query['type']) && $query['type'] == "Doctor") {
                $appointments = $patient->doctor_appointments()
                                         ->with('doctor.user') // Ensure doctor and associated user data are loaded
                                         ->get()
                                         ->map(function ($appointment) {
                                             $appointment->name = $appointment->doctor->user->name ?? 'Unknown';
                                             return $appointment;
                                         });
    
                $totalLength = $appointments->count();
            } else if (isset($query['type']) && $query['type'] == "Nurse") {
                $appointments = $patient->nurse_appointments()
                                         ->with('nurse.user')
                                         ->get()
                                         ->map(function ($appointment) {
                                             $appointment->name = $appointment->nurse->user->name ?? 'Unknown';
                                             return $appointment;
                                         });
    
                $totalLength = $appointments->count();
            } else {
                return response()->json(['error' => 'Invalid type specified. Please use "Doctor" or "Nurse".'], 400);
            }
    
            if (isset($query['page']) && isset($query['rowsPerPage'])) {
                $page = (int) $query['page'];
                $rowsPerPage = (int) $query['rowsPerPage'];
                $appointments = $appointments->slice($page * $rowsPerPage, $rowsPerPage)->values();
            }
            
            return response()->json([
                'appointments' => $appointments,
                'total' => $totalLength
            ]);
        }
        catch(Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    // Method to get doctor appointments for a patient
    public function getDoctorAppointments(Patient $patient)
    {
        try {
            $patient = Patient::findOrFail($patient->id);
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
            $patient = Patient::findOrFail($patient->id);
            $appointments = $patient->nurse_appointments;
            return response()->json($appointments);
        }
        catch(Exception $e) {
            return response()->json($e->getMessage());
        } 
    }

    public function getPrescriptions(Patient $patient) {
        try {
            return response()->json(PrescriptionsResource::collection($patient->prescriptions));
        }
        catch(Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function uploadPrescription(Patient $patient, StorePrescriptionsRequest $request) {
        try {
            DB::beginTransaction();
            $data = $request->all();

            $doctor = Doctor::findOrFail($data['doctor_id']);

            $prescription = new Prescriptions();
            $prescription->prescription_image = app('App\Http\Controllers\API\AuthController')->uploadFileToCloudinary($request, 'prescription_image');
            $prescription->patient_id = $patient->id;
            $prescription->doctor_id = $doctor->id;

            $prescription->save();
            DB::commit();
            return response()->json(PrescriptionsResource::make($prescription));
        }
        catch(Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function paymentAndReserve(Request $request) {
        try {
            DB::beginTransaction();
            $data = $request->all();
            $payment = new Payment();
            
            $payment->amount = $data['amount'];
            $payment->order_id = $data['order_id'];
            $payment->patient_id = $data['patient_id'];

            $appointment = null;

            if ($data['medic_role'] == "doctor") {
                $appointment = new DoctorAppointment();
                $appointment->doctor_id = $data['medic_id'];
                $appointment->kind_of_visit = $data['kind_of_visit'];
            }
            else {
                $appointment = new NurseAppointment();
                $appointment->nurse_id = $data['medic_id'];
            }

            $appointment->patient_id = $data['patient_id'];
            $appointment->day = $data['day'];
            $appointment->date = $data['date'];

            $payment->save();
            $appointment->save();

            DB::commit();

            return response()->json([
                $payment,
                $appointment,
            ]);
        }
        catch(Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
