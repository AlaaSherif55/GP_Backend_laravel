<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntensiveCareEquipment extends Model
{
    use HasFactory;
    public function intensiveCareUnits()
    {
        return $this->belongsToMany(IntensiveCareUnit::class, 'intensive_care_equipment');
    }
}
