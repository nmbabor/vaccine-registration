<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaccinationRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'nid',
        'mobile_number',
        'vaccine_center_id',
        'scheduled_date'
    ];

     // Define relationship with Vaccine Center
     public function vaccineCenter()
     {
         return $this->belongsTo(VaccineCenter::class);
     }
}
