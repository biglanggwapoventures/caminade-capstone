<?php

namespace App;

use App\OrderLine;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_name',
        'customer_id',
        'order_date',
        'remarks',
    ];

    protected $appends = [
        'total_amount',
        'order_type',
    ];

    public function scopeFieldsForMasterList($query)
    {
        return $query->with(['line', 'customer'])->orderBy('id', 'desc');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function line()
    {
        return $this->hasMany(OrderLine::class, 'order_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function paypalTransaction()
    {
        return $this->hasOne(PaypalTransaction::class, 'order_id');
    }

    public function getTotalAmountAttribute()
    {
        if ($this->relationLoaded('line')) {
            return $this->line->sum('amount');
        }

        return 0;
    }

    public function getOrderTypeAttribute()
    {
        return $this->customer_id ? 'IN_HOUSE' : 'WALK_IN';
    }

    public function scopeWithCustomerName($query, $name)
    {
        return $query->whereHas('customer', function ($q) use ($name) {
            $q->whereRaw("CONCAT(firstname, ' ', lastname) LIKE '%{$name}%'");
        })
                     ->orWhere('customer_name', 'LIKE', "'%{$name}%'");
    }

    public function scopeWithDetails($query)
    {
        return $query->with('line.product');
    }
}
