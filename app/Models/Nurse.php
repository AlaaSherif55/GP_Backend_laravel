<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nurse extends Model
{
    use HasFactory;
    protected $fillable = [
        'image',
        'university',
        'qualifications' ,
        'city' ,
        'fees',
        'work_start',
        'work_end',
        'work_days',
        
    ];
    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function ratings()
    {
        return $this->hasMany(NurseRating::class);
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
