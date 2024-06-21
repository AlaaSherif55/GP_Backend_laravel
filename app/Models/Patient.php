<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'history',
        'gender',
        'birth_date',
        
    ];
    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function doctor_ratings() 
    {
        return $this->hasMany(DoctorRating::class);
    }

    public function nurse_ratings() 
    {
        return $this->hasMany(NurseRating::class);
    }

    public function doctor_appointments() 
    {
        return $this->hasMany(DoctorAppointment::class);
    }

    public function nurse_appointments() 
    {
        return $this->hasMany(NurseAppointment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

}
