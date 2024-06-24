<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;
    protected $fillable = [
        'verification_status',
    ];

    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function units()
    {
        return $this->hasMany(IntensiveCareUnit::class);
    }
}
