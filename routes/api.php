<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use \App\Http\Controllers\API\DoctorController;
use \App\Models\Doctor;
use \App\Models\Nurse;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('hospitals/{hospital}/applications', [\App\Http\Controllers\API\IntensiveCareApplicationController::class, 'getApplications']);
Route::put('/applications/{application}', [\App\Http\Controllers\API\IntensiveCareApplicationController::class, 'updateStatus']);
Route::apiResource('/intensive-care-units', \App\Http\Controllers\API\IntensiveCareUnitController::class);
Route::apiResource('/intensive-care-applications', \App\Http\Controllers\API\IntensiveCareApplicationController::class);

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
    $res = $query->with('user')->paginate(5);

    return response()->json($res);
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
    $res = $query->with('user')->paginate(5);

    return response()->json($res);
});