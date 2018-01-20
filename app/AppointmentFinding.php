<?php

namespace App;

use App\Appointment;
use App\Pet;
use Illuminate\Database\Eloquent\Model;

class AppointmentFinding extends Model
{
    protected $fillable = [
        'appointment_id',
        'pet_id',
        'findings',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }
}
