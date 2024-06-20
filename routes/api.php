<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\DoctorController ;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('hospitals/{hospital}/applications', [\App\Http\Controllers\API\IntensiveCareApplicationController::class, 'getApplications']);
Route::put('/applications/{application}', [\App\Http\Controllers\API\IntensiveCareApplicationController::class, 'updateStatus']);
Route::apiResource('/intensive-care-units', \App\Http\Controllers\API\IntensiveCareUnitController::class);
Route::apiResource('/intensive-care-applications', \App\Http\Controllers\API\IntensiveCareApplicationController::class);
Route::apiResource("doctors",DoctorController::class);
Route::get("/doctors/{doctor}/appointments",[DoctorController::class,"getDoctorAppointments"]); 
Route::patch("/doctors/appointments/{appointment}/approve",[DoctorController::class,"ApproveDoctorAppointments"]); 
Route::patch("/doctors/appointments/{appointment}/add-notes",[DoctorController::class,"AddNoteToDoctorAppointments"]); 