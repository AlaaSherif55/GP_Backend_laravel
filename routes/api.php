<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Registeration
Route::post('DoctorRegister', [AuthController::class, 'doctorRegister']);
Route::post('PatientRegister', [AuthController::class, 'patientRegister']);
Route::post('NurseRegister', [AuthController::class, 'nurseRegister']);

// Login
Route::post('login', [AuthController::class, 'login'] )->middleware('role:nurse');  //without token

// Get user data from token (nurse-doctor-patient)
Route::get('user', [AuthController::class, 'getUser'] )->middleware('auth:sanctum'); //token any role

