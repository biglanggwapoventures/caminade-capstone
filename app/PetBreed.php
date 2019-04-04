<?php

namespace App;

use App\Pet;
use App\PetCategory;
use Illuminate\Database\Eloquent\Model;

class PetBreed extends Model
{
    protected $fillable = [
        'pet_category_id',
        'description',
    ];

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    public function category()
    {
        return $this->belongsTo(PetCategory::class, 'pet_category_id');
    }

    public function scopeFieldsForMasterList($query)
    {
        return $query->orderBy('description');
    }

    public static function dropdownFormat()
    {
        return self::orderBy('description')->pluck('description', 'id')->prepend('', '');
    }
}
