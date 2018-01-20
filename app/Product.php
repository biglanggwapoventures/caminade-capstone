<?php

namespace App;

use App\ProductCategory;
use App\ProductLog;
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
        'product_status',
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
        return $query->with(['supplier', 'category'])->orderBy('product_status')->orderBy('name');
    }

    public function getPhotoSrcAttribute()
    {
        return asset("storage/{$this->photo_path}");
    }

    public function beginningBalanceLog()
    {
        return $this->morphOne(ProductLog::class, 'log', 'causer', 'causer_id');
    }

    public function setBeginningBalance($quantity = null)
    {
        $log = $this->beginningBalanceLog()->firstOrNew([
            'quantity' => $quantity ?: $this->stock,
            'product_id' => $this->id,
            'remarks' => 'begnning balance',
        ]);
        $log->save();
        return $log;
    }

    public function logDecrements()
    {
        # code...
    }
}
