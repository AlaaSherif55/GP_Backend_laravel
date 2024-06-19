<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'image', 
    ];

    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function ratings()
    {
        return $this->hasMany(DoctorRating::class);
    }

    public function appointments()
    {
        return $this->hasMany(DoctorAppointment::class);
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
