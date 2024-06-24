<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NurseAppointment extends Model
{
    use HasFactory;
    protected $fillable = ['patient_id', 'Nurse_id', 'day', 'date', 'status', 'notes'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

}
