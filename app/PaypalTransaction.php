<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaypalTransaction extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'transaction_data'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'transaction_data' => 'array'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
