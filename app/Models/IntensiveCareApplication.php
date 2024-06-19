<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntensiveCareApplication extends Model
{
    protected  $fillable = [
        'patient_name',
        'patient_phone',
        'intensive_care_unit_id',
        'status',
        'description'
    ];
    use HasFactory;
    public function intensiveCareUnit()
{
    return $this->belongsTo(IntensiveCareUnit::class);
}

}
