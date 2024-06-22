<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\DoctorController ;
use App\Http\Controllers\API\NurseController ;
use App\Http\Controllers\API\DoctorRatingsController ;
use App\Http\Resources\DoctorRatingResource ;

use \App\Models\Doctor;
use \App\Models\Nurse;
use \App\Models\DoctorRating;
use \App\Models\NurseRating;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('hospitals/{hospital}/applications', [\App\Http\Controllers\API\IntensiveCareApplicationController::class, 'getApplications']);
Route::put('/applications/{application}', [\App\Http\Controllers\API\IntensiveCareApplicationController::class, 'updateStatus']);
Route::get('/icus',[\App\Http\Controllers\API\IntensiveCareUnitController::class,'getAllICUs']);
Route::get('/intensive-care-units/{hospital}', [\App\Http\Controllers\API\IntensiveCareUnitController::class, 'getHospitalICUs']);
Route::apiResource('/intensive-care-units', \App\Http\Controllers\API\IntensiveCareUnitController::class);
Route::apiResource('/intensive-care-applications', \App\Http\Controllers\API\IntensiveCareApplicationController::class);

Route::apiResource('/equipment', \App\Http\Controllers\API\EquipmentController::class);

Route::apiResource("doctors",DoctorController::class);
Route::get("/doctors/{doctor}/appointments",[DoctorController::class,"getDoctorAppointments"]); 
Route::patch("/doctors/appointments/{appointment}/approve",[DoctorController::class,"ApproveDoctorAppointments"]); 
Route::patch("/doctors/appointments/{appointment}/add-notes",[DoctorController::class,"AddNoteToDoctorAppointments"]); 
Route::apiResource("nurses",NurseController::class);



// Get Doctors
Route::get('doctors', function (Request $request) {
    $query = Doctor::query();

    if ($request->has('city') && $request->input('city') !== '')
    {
        $query->where('city' ,$request->input('city'));
    }

    if ($request->has('specialization') && $request->input('specialization') !== '')
    {
        $query->where('specialization', $request->input('specialization'));
    }

    if ($request->has('available') && $request->input('available') !== '')
    {
        $query->where('work_days', 'like', '%'.$request->input('available').'%');
    }

    if ($request->has('fees') && $request->input('fees') !== '')
    {
        $query->where('clinic_fees', '<=', $request->input('fees'));
    }
    $doctors = $query->with('user')->paginate(5);
    
    $doctors->getCollection()->transform(function ($doctor) {
    $doctor->average_rating = $doctor->averageRating();
        return $doctor;
    });

    return response()->json($doctors);
});

// get doctor
Route::get('doctors/{id}', function ($id) {
    $doctor = Doctor::with('ratings')->with('averageRating')->findOrFail($id);
    return $doctor;
});

// get Nurses
Route::get('nurses', function (Request $request) {
    $query = Nurse::query();

    if ($request->has('city') && $request->input('city') !== '')
    {
        $query->where('city' ,$request->input('city'));
    }

    if ($request->has('available') && $request->input('available') !== '')
    {
        $query->where('work_days', 'like', '%'.$request->input('available').'%');
    }

    if ($request->has('fees') && $request->input('fees') !== '')
    {
        $query->where('fees', '<=', $request->input('fees'));
    }
    $nurses = $query->with('user')->paginate(5);

    $nurses->getCollection()->transform(function ($nurse) {
    $nurse->average_rating = $nurse->averageRating();
        return $nurse;
    });
    return response()->json($nurses);
});

// get nurse
Route::get('nurses/{id}', function ($id) {
    $nurse = Nurse::with('user')->with('ratings')->findOrFail($id);
    return $nurse;
});

// get doctor reviews
Route::get('doctors/{id}/reviews', function($id) {
        $ratings = DoctorRating::with('patient.user')
        ->where('doctor_id', $id)
        ->paginate(5);

    return response()->json([
        "status" => "success",
        "data" => $ratings
    ]);
});

// get nurse ratings
Route::get('nurses/{id}/reviews', function($id) {
        $ratings = NurseRating::with('patient.user')
        ->where('nurse_id', $id)
        ->paginate(5);

    return response()->json([
        "status" => "success",
        "data" => $ratings
    ]);
});