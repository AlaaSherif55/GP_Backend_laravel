<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'image', 'university', 'qualifications', 'city', 'address', 'clinic_fees', 'home_fees', 'online', 'specialization', 'visit', 'clinic_work_start', 'clinic_work_end', 'home_work_start', 'home_work_end', 'work_days'
    ];

    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function ratings()
    {
        return $this->hasMany(DoctorRating::Class);
    }

    public function appointments()
    {
        return $this->hasMany(DoctorAppointment::Class);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }
    
    public function averageRating()
    {
        return $this->ratings()->avg('rating');
    }
}
