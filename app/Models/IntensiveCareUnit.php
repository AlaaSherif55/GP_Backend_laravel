<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntensiveCareUnit extends Model
{
    use HasFactory;

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function applications()
    {
        return $this->hasMany(IntensiveCareApplication::class);
    }

    public function equipments()
    {
        return $this->belongsToMany(Equipment::class, 'intensive_care_unit_eqipments');
    }
}

