<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'description',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function scopeFieldsForMasterList($query)
    {
        return $query;
    }
}
