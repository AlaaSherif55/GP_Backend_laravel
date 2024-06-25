<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorAppointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id', 'doctor_id', 'kind_of_visit', 'day', 'status', 'notes'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }


    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
