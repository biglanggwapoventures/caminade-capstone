<?php

namespace App;

use App\Boarding;
use App\Product;
use Illuminate\Database\Eloquent\Model;

class BoardingProductsUsed extends Model
{
    protected $fillable = [
        'boarding_id',
        'product_id',
        'quantity',
        'unit_price',
        'discount',
        'date_used',
        'time_used',
    ];

    protected $casts = [
        'quantity' => 'int',
        'unit_price' => 'float',
        'discount' => 'float',
    ];

    public function boarding()
    {
        return $this->belongsTo(Boarding::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function setTimeUsedAttribute($value)
    {
        $this->attributes['time_used'] = date_create_from_format('H:i', $value)->format('H:i:s');
    }

    public function getTimeUsedAttribute($value)
    {
        return date_create_from_format('H:i:s', $value)->format('H:i');
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

        $data['remarks'] = "Boarding # {$this->id} (Used Product)";
        return $this->productLog()->create($data);
    }
}
