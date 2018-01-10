<?php

namespace App;

use App\Pet;
use App\Service;
use Illuminate\Database\Eloquent\Model;

class AppointmentLine extends Model
{
    protected $fillable = [
        'appointment_id',
        'service_id',
        'pet_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function scopeFieldsForMasterList($query)
    {
        return $query;
    }
}
