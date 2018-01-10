<?php

namespace App;

use App\Appointment;
use App\Pet;
use Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'facebook_uid',
        'google_uid',
        'email',
        'password',
        'firstname',
        'middlename',
        'lastname',
        'gender',
        'contact_number',
        'address',
        'role',
        'active',
    ];

    protected $appends = [
        'fullname',
        'registration_method',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    public function scopeFieldsForMasterList($query)
    {
        return $query;
    }

    public function setPasswordAttribute($val)
    {
        $this->attributes['password'] = Hash::make($val);
    }

    public function getFullNameAttribute()
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function is($role)
    {
        return $this->role == strtoupper($role);
    }

    public function getRegistrationMethodAttribute()
    {
        if (is_null($this->facebook_uid) && is_null($this->google_uid)) {
            return 'ON SITE';
        }

        return is_null($this->facebook_uid) ? 'Google' : 'Facebook';
    }

    public function appointmentsAsCustomer()
    {
        return $this->hasMany(Appointment::class, 'customer_id');
    }

    public function appointmentsAsDoctor()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function scopeOfRole($query, $role)
    {
        return $query->whereRole(strtoupper($role));
    }
    public static function customerList()
    {
        return self::ofRole('customer')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => "{$item->fullname} [{$item->username}]"];
            });
    }
}
