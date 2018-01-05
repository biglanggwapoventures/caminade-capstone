<?php

namespace App;

use App\ProductCategory;
use App\Supplier;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_category_id',
        'supplier_id',
        'name',
        'code',
        'description',
        'price',
        'stock',
        'reorder_level',
        'photo_path',
    ];

    protected $casts = [
        'reorder_level' => 'float',
        'stock' => 'float',
        'price' => 'float',
    ];

    protected $appends = [
        'photo_src',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function scopeFieldsForMasterList($query)
    {
        return $query;
    }

    public function getPhotoSrcAttribute()
    {
        return asset("storage/{$this->photo_path}");
    }
}
