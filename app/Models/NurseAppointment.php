<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NurseAppointment extends Model
{
    use HasFactory;

    public function appointments()
    {
        return $this->hasMany(DoctorAppointment::Class);
    }

}
