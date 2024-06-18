<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDoctorAppointmentRequest;
use App\Http\Requests\UpdateDoctorAppointmentRequest;
use App\Models\DoctorAppointment;

class DoctorAppointmentController extends Controller
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
    public function store(StoreDoctorAppointmentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DoctorAppointment $doctorAppointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDoctorAppointmentRequest $request, DoctorAppointment $doctorAppointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DoctorAppointment $doctorAppointment)
    {
        //
    }
}
