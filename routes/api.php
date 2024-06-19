<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('DoctorRegister', [AuthController::class, 'doctorRegister'])->middleware('role:any');
Route::post('PatientRegister', [AuthController::class, 'patientRegister'])->middleware('role:any');