<?php

namespace App;

use App\Appointment;
use App\Product;
use Illuminate\Database\Eloquent\Model;

class AppointmentProduct extends Model
{
    protected $fillable = [
        'appointment_id',
        'product_id',
        'quantity',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'quantity' => 'int',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}