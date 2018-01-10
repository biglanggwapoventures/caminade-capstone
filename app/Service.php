<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'description',
        'duration',
        'price',
    ];

    protected $casts = [
        'price' => 'float',
        'duration' => 'int',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function scopeFieldsForMasterList($query)
    {
        return $query;
    }
}
