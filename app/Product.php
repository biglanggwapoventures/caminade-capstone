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
        'stock_on_hand',
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
        return $query->with(['supplier', 'category', 'logs'])->orderBy('product_status')->orderBy('name');
    }

    public function getPhotoSrcAttribute()
    {
        return asset("storage/{$this->photo_path}");
    }

    public function logs()
    {
        return $this->hasMany(ProductLog::class, 'product_id');
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
            'remarks' => 'Beginning Balance',
        ]);
        $log->save();
        return $log;
    }

    public function adjustQuantity($quantity)
    {
        $this->logs()->create([
            'causer' => get_class($this),
            'causer_id' => $this->id,
            'remarks' => 'Stock Adjustment @' . now()->format('m/d/Y h:i A'),
            'quantity' => $quantity,
        ]);
    }

    public function getStockOnHandAttribute()
    {
        if ($this->relationLoaded('logs')) {
            return $this->logs->sum('quantity');
        }
        return 0;
    }
}
