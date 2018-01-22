<?php

namespace App;

use App\AppointmentFinding;
use App\AppointmentLine;
use App\AppointmentProduct;
use App\Facades\SMS;
use App\PetLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'customer_id',
        'doctor_id',
        'remarks',
        'appointment_date',
        'appointment_time',
        'appointment_status',
        'status_remarks',
        'completed_at',
    ];

    protected $appends = [
        'appointment_timestamp',
    ];

    public function calculateApproximateFinishTime()
    {
        $startTime = date_create_immutable_from_format('Y-m-d H:i', "{$this->appointment_date} {$this->appointment_time}");
        $duration = $this->calculateDuration();

        $this->approximate_finish_time = $startTime->modify("+ {$duration} minutes")->format('Y-m-d H:i');
        return $this->approximate_finish_time;
    }

    public function calculateDuration()
    {
        return $this->line->sum('service.duration');
    }

    public function calculateTotalServiceAmount()
    {
        return $this->line->sum('service.price');
    }

    public function calculateTotalProductAmount()
    {
        return $this->usedProducts->sum(function ($used) {
            return $used->quantity * $used->product->price;
        });
    }

    public function calculateTotalAmount()
    {
        return $this->calculateTotalServiceAmount() + $this->calculateTotalServiceAmount();
    }

    public function setAppointmentTimeAttribute($val)
    {
        $this->attributes['appointment_time'] = date_create_immutable_from_format('H:i', $val)->format('H:i:s');
    }

    public function getAppointmentTimeAttribute($val)
    {
        return $val ? date_create_immutable_from_format('H:i:s', $val)->format('H:i') : null;
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function findings()
    {
        return $this->hasMany(AppointmentFinding::class, 'appointment_id');
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
        return strtolower($status) === strtolower($this->appointment_status);
    }

    public function scopeOfStatus($query, $status)
    {
        return $query->whereAppointmentStatus(strtoupper($status));
    }

    public function markAsApproved()
    {
        $this->appointment_status = 'CANCELLED';
        $this->save();
        return $this;
    }

    public function scopeOfCustomer($query, $cusomerId)
    {
        return $query->whereCustomerId($cusomerId);
    }

    public function scopeOfDoctor($query, $doctorId)
    {
        return $query->whereDoctorId($doctorId);
    }

    public function scopeApproved($query)
    {
        return $query->whereAppointmentStatus('APPROVED');
    }

    public function loadDetails()
    {
        return $this->load(['customer', 'line.service', 'line.pet.breed', 'usedProducts.product']);
    }

    public function getAppointmentStatusAttribute($val)
    {
        return $this->completed_at ? 'COMPLETED' : $val;
    }

    public function petLogs()
    {
        return $this->hasMany(PetLog::class);
    }

    public function sendApprovalSMS()
    {
        $timestamp = $this->appointment_timestamp->format('m/d/y h:i A');
        $message = new SMS($this->customer->contact_number, "PetCare: Your appointment on {$timestamp} has been APPROVED! Please be guided. Thank you!");
        return $message->send();
    }

    public function sendRejectionSMS()
    {
        $timestamp = $this->appointment_timestamp->format('m/d/y h:i A');
        $message = new SMS($this->customer->contact_number, "PetCare: Your appointment on {$timestamp} has been REJECTED! Please be guided. Thank you!");
        return $message->send();
    }

    public function sendUpdateSMS()
    {
        $appointmentTime = date_create_from_format('H:i', $this->appointment_time)->format('h:i a');
        $message = new SMS($this->customer->contact_number, "PetCare: You have an appointment today @ {$appointmentTime}. Please be guided. Thank you!");
        return $message->send();
    }

    public function getAppointmentTimestampAttribute()
    {
        return Carbon::createFromFormat('Y-m-d H:i', "{$this->appointment_date} {$this->appointment_time}", 'Asia/Manila');
    }

}
