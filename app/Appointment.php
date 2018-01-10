<?php

namespace App;

use App\AppointmentLine;
use App\AppointmentProduct;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'customer_id',
        'doctor_id',
        'remarks',
        'findings',
        'appointment_date',
        'appointment_time',
        'appointment_status',
        'status_remarks',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id')->withDefault(function ($doctor) {
            $doctor->firstname = 'N/A';
        });
    }

    public function line()
    {
        return $this->hasMany(AppointmentLine::class);
    }

    public function usedProducts()
    {
        return $this->hasMany(AppointmentProduct::class);
    }

    public function scopeFieldsForMasterList($query)
    {
        return $query->orderBy('id', 'desc')
            ->with([
                'line' => function ($line) {
                    return $line->with(['pet', 'service']);
                },
                'customer',
                'doctor',
            ]);
    }

    public function getTotalAmount()
    {
        return $this->line->sum('service.price') + $this->usedProducts->sum(function ($used) {
            return $used->quantity * $used->product->price;
        });
    }

    public function is($status)
    {
        return strtoupper($status) === $this->appointment_status;
    }

}
