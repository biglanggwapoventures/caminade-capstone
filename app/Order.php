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
        'customer_id',
        'order_date',
        'remarks',
    ];

    protected $appends = [
        'total_amount',
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

    public function getTotalAmountAttribute()
    {
        if ($this->relationLoaded('line')) {
            return $this->line->sum('amount');
        }
        return 0;
    }
}
