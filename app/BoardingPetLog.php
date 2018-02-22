<?php

namespace App;

use App\Boarding;
use App\Pet;
use Illuminate\Database\Eloquent\Model;

class BoardingPetLog extends Model
{
    protected $fillable = [
        'boarding_id',
        'pet_id',
        'log_date',
        'log_time',
        'remarks',
    ];

    protected $appends = [
        'timestamp',
    ];

    public function boarding()
    {
        return $this->belongsTo(Boarding::class);
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function setLogTimeAttribute($value)
    {
        $this->attributes['log_time'] = date_create_from_format('H:i', $value)->format('H:i:s');
    }

    public function getLogTimeAttribute($value)
    {
        return date_create_from_format('H:i:s', $value)->format('H:i');
    }

    public function getTimestampAttribute()
    {
        return date_create_from_format('Y-m-d H:i', "{$this->log_date} {$this->log_time}");
    }
}
