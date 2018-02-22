<?php

namespace App;

use App\Appointment;
use App\BoardingPetLog;
use App\BoardingProductsUsed;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Boarding extends Model
{
    protected $fillable = [
        'timestamp_in',
        'timestamp_out',
        'appointment_id',
        'price_per_day',
    ];

    protected $appends = [
        'total_payable',
        'duration',
    ];

    public function scopeFieldsForMasterList($query)
    {
        return $query;
    }

    public function productsUsed()
    {
        return $this->hasMany(BoardingProductsUsed::class);
    }

    public function petJournals()
    {
        return $this->hasMany(BoardingPetLog::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function getDurationAttribute()
    {
        $endDate = $this->timestamp_out ? Carbon::createFromFormat('Y-m-d H:i:s', $this->timestamp_out) : now();
        $duration = $endDate->diffInDays(Carbon::createFromFormat('Y-m-d H:i:s', $this->timestamp_in)) ?: 1;
        return $duration;
    }

    public function getTotalPayableAttribute()
    {
        if ($this->relationLoaded('productsUsed')) {
            $totalProductsUsed = $this->productsUsed->sum(function ($item) {
                return (($item->unit_price - $item->discount) * $item->quantity);
            });

            return $totalProductsUsed + ($this->duration * $this->price_per_day);
        }
        return 0;
    }
}
