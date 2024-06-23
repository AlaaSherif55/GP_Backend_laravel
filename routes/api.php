<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\DoctorController ;
use App\Http\Controllers\API\NurseController ;
use App\Http\Controllers\API\DoctorRatingsController ;
use App\Http\Resources\DoctorRatingResource ;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PatientController;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;
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
Route::get("/doctors/{doctor}/prescriptions",[DoctorController::class,"getDoctorPrescriptions"]); 
Route::patch("/doctors/prescriptions/{prescription}/reply",[DoctorController::class,"ReplyToDoctorPrescription"]); 

Route::get("/doctors/{doctor}/appointments",[DoctorController::class,"getDoctorAppointments"]); 
Route::patch("/doctors/appointments/{appointment}/approve",[DoctorController::class,"ApproveDoctorAppointments"]); 
Route::patch("/doctors/appointments/{appointment}/add-notes",[DoctorController::class,"AddNoteToDoctorAppointments"]); 

Route::apiResource("nurses",NurseController::class);
Route::get("/nurses/{nurse}/appointments",[NurseController::class,"getNurseAppointments"]); 
Route::patch("/nurses/appointments/{appointment}/approve",[NurseController::class,"ApproveNurseAppointments"]); 
Route::patch("/nurses/appointments/{appointment}/add-notes",[NurseController::class,"AddNoteToNurseAppointments"]); 

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
        if (strpos($request->input('available'), ',') !== false) 
        {
            $days = explode(',', $request->input('available'));
            $query->where('work_days', 'like', '%'.$days[0].'%');
            $query->OrWhere('work_days', 'like', '%'.$days[1].'%');
        }
        else 
        {
            $query->where('work_days', 'like', '%'.$request->input('available').'%');
        }
    }

    if ($request->has('fees') && $request->input('fees') !== '')
    {
        $query->where('clinic_fees', '<=', $request->input('fees'));
    }

    if ($request->has('name') && $request->input('name') !== '') 
    {
        $name = $request->input('name');
        $query->whereHas('user', function ($q) use ($name) {
            $q->where('name', 'like', '%' . $name . '%');
        });
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
        if (strpos($request->input('available'), ',') !== false) 
        {
            $days = explode(',', $request->input('available'));
            $query->where('work_days', 'like', '%'.$days[0].'%');
            $query->OrWhere('work_days', 'like', '%'.$days[1].'%');
        }
        else 
        {
            $query->where('work_days', 'like', '%'.$request->input('available').'%');
        }
    }

    if ($request->has('fees') && $request->input('fees') !== '')
    {
        $query->where('fees', '<=', $request->input('fees'));
    }


    if ($request->has('name') && $request->input('name') !== '') 
    {
        $name = $request->input('name');
        $query->whereHas('user', function ($q) use ($name) {
            $q->where('name', 'like', '%' . $name . '%');
        });
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

// Registeration
Route::post('DoctorRegister', [AuthController::class, 'doctorRegister']);
Route::post('PatientRegister', [AuthController::class, 'patientRegister']);
Route::post('NurseRegister', [AuthController::class, 'nurseRegister']);
Route::post('HospitalRegister', [AuthController::class, 'hospitalRegister']);

// Login
Route::post('login', [AuthController::class, 'login'] );  //without token

// Get user data from token (nurse-doctor-patient)
Route::get('user', [AuthController::class, 'getUser'] )->middleware('auth:sanctum'); //token any role

// Routes for email verification

Route::get('/email/verify/{id}', function () {

    // return redirect(env('FRONT_URL'));
return redirect(env('FRONT_URL').'verify');

})->name('verification.verify');


Route::post('/email/verified', function (Request $request) {
    $request->validate([
        'timestamp' => 'required|date',
        'email' => 'required|email', 
    ]);

    // Find the user by email
    $user = App\Models\User::where('email', $request->email)->first();

    if ($user) {
        $user->email_verified_at = now();
        $user->save();

        return response()->json(['message' => 'Email verified successfully']);
    } else {
        return response()->json(['error' => 'User not found'], 404);
    }
})->middleware('auth:sanctum')->name('verified');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);
 
    $status = Password::sendResetLink(
        $request->only('email')
    );
 
    return $status === Password::RESET_LINK_SENT
                ? response()->json(['status' => __($status)])
                : response()->json(['error' => __($status)]);
})->middleware('guest')->name('password.email');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60), 
            ]);

            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? response()->json(['status', __($status)])
        : response()->json(['error' => [__($status)]]);
})->middleware('guest')->name('password.update');

Route::apiResource("patients", PatientController::class);

Route::get('patients/{patient}/appointments', [PatientController::class, 'getAllAppointments']);
Route::get('patients/{patient}/appointments/doctors', [PatientController::class, 'getDoctorAppointments']);
Route::get('patients/{patient}/appointments/nurses', [PatientController::class, 'getNurseAppointments']);
Route::post('patients/{patient}/prescription', [PatientController::class, 'uploadPrescription']);
Route::get('patients/{patient}/prescription', [PatientController::class, 'getPrescriptions']);

