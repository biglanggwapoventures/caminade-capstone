<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\Model;

class OrderLine extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'discount',
    ];

    protected $appends = [
        'net_unit_price',
        'amount',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id')->whereNull('deleted_at');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getNetUnitPriceAttribute()
    {
        return $this->unit_price - $this->discount;
    }

    public function getAmountAttribute()
    {
        return $this->net_unit_price * $this->quantity;
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

        $data['remarks'] = "Purchase # {$this->order_id}";
        return $this->productLog()->create($data);
    }

    public function setDiscountAttribute($val)
    {
        $this->attributes['discount'] = $val ?: 0;
    }
}
