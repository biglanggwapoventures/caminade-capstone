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

    public function productLog()
    {
        return $this->morphOne(ProductLog::class, 'log', 'causer', 'causer_id');
    }

    public function saveProductLog($quantity = null)
    {
        $data = [
            'quantity' => ($quantity ?: $this->quantity) * -1,
            'product_id' => $this->product_id,
        ];
        if ($this->productLog()->exists()) {
            return $this->productLog()->update($data);
        }

        $data['remarks'] = "Appointment # {$this->appointment_id} (Used Product)";
        return $this->productLog()->create($data);
    }
}
