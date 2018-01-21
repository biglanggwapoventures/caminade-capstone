<?php

namespace App;

use App\Appointment;
use App\Pet;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PetLog extends Model
{
    protected $fillable = [
        'appointment_id',
        'pet_id',
        'remarks',
        'log_date',
        'log_time',
    ];

    protected $appends = [
        'timestamp',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function setLogTimeAttribute($val)
    {
        $this->attributes['log_time'] = date_create_immutable_from_format('H:i', $val)->format('H:i:s');
    }

    public function getLogTimeAttribute($val)
    {
        return $val ? date_create_immutable_from_format('H:i:s', $val)->format('H:i') : null;
    }

    public function getTimestampAttribute()
    {
        return Carbon::createFromFormat('Y-m-d H:i', "{$this->log_date} {$this->log_time}", 'Asia/Manila');
    }

}
