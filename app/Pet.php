<?php

namespace App;

use App\AppointmentFinding;
use App\PetBreed;
use App\PetCategory;
use App\PetReproductiveAlteration;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $fillable = [
        'user_id',
        'pet_category_id',
        'pet_breed_id',
        'pet_reproductive_alteration_id',
        'name',
        'birthdate',
        'gender',
        'color',
        'weight',
        'physical_characteristics',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function breed()
    {
        return $this->belongsTo(PetBreed::class, 'pet_breed_id');
    }

    public function category()
    {
        return $this->belongsTo(PetCategory::class);
    }

    public function reproductiveAlteration()
    {
        return $this->belongsTo(PetReproductiveAlteration::class, 'pet_reproductive_alteration_id');
    }

    public function scopeFieldsForMasterList($query)
    {
        return $query->with(['breed.category', 'reproductiveAlteration']);
    }

    public function scopeOwnedBy($query, $userId)
    {
        return $query->whereUserId($userId);
    }

    public function scopeOfCustomer($query, $customerId)
    {
        return $query->with('breed')->ownedBy($customerId);
    }

    public function scopeToDropdownFormat()
    {
        return $this->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => "{$item->name} ({$item->breed->description})"];
            })
            ->prepend('', '');
    }

    public function findings()
    {
        return $this->hasMany(AppointmentFinding::class)->whereHas('appointment', function ($q) {
            $q->whereNotNull('completed_at');
        })->orderBy('created_at', 'desc');
    }

    public function petLogs()
    {
        return $this->hasMany(PetLog::class)->whereHas('appointment', function ($q) {
            $q->whereAppointmentStatus('APPROVED');
        })->orderBy('log_date', 'desc')->orderBy('log_time', 'desc');
    }

    public function medicalHistory()
    {
        return $this->findings()->with('appointment.doctor')->get();
    }

    public function logs()
    {
        return $this->petLogs()->get();
    }

}
