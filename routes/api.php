<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('hospitals/{hospital}/applications', [\App\Http\Controllers\API\IntensiveCareApplicationController::class, 'getApplications']);
Route::put('/applications/{application}', [\App\Http\Controllers\API\IntensiveCareApplicationController::class, 'updateStatus']);
Route::get('/icus',[\App\Http\Controllers\API\IntensiveCareUnitController::class,'getAllICUs']);
Route::get('/intensive-care-units/{hospital}', [\App\Http\Controllers\API\IntensiveCareUnitController::class, 'getHospitalICUs']);
Route::apiResource('/intensive-care-units', \App\Http\Controllers\API\IntensiveCareUnitController::class);
Route::apiResource('/intensive-care-applications', \App\Http\Controllers\API\IntensiveCareApplicationController::class);

