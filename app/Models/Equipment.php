<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function intensiveCareUnits()
    {
        return $this->belongsToMany(IntensiveCareUnit::class, 'intensive_care_unit_equipments');
    }
}
