<?php

namespace App;

use App\Appointment;
use App\DoctorProfile;
use App\Order;
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
        'role_title',
        'active',
    ];

    protected $appends = [
        'fullname',
        'registration_method',
        'is_blocked',
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
        return $query->orderBy('firstname');
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
        $roles = array_wrap($role);
        return in_array(strtolower($this->role), array_map('strtolower', $roles));
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

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function ordersWithDetails()
    {
        return $this->orders()->with('line.product')->orderBy('id', 'desc');
    }

    public static function customerList()
    {
        return self::ofRole('customer')
            ->orderBy('firstname')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => "{$item->fullname} [{$item->username}]"];
            });
    }

    public function scopeUnblocked($query)
    {
        return $query->whereNull('blocked_at')->whereNull('blocked_by');
    }

    public function scopeLoginStatus($query, $status)
    {
        if ($status === 'unblocked') {
            return $query->whereNull('blocked_at')->whereNull('blocked_by');
        }

        if ($status === 'blocked') {
            return $query->whereNotNull('blocked_at')->whereNotNull('blocked_by');
        }

        return $query;
    }

    public function getIsBlockedAttribute()
    {
        return $this->blocked_by && $this->blocked_at;
    }

    public function blocked()
    {
        return $this->belongsTo(get_class($this), 'blocked_by');
    }

    public function doctorProfile()
    {
        return $this->hasOne(DoctorProfile::class, 'doctor_id');
    }
}
