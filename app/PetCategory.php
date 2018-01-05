<?php

namespace App;

use App\Pet;
use App\PetBreed;
use Illuminate\Database\Eloquent\Model;

class PetCategory extends Model
{
    protected $fillable = [
        'description',
    ];

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    public function breeds()
    {
        return $this->hasMany(PetBreed::class);
    }

    public function scopeFieldsForMasterList($query)
    {
        return $query;
    }

    public static function dropdownFormat()
    {
        return self::orderBy('description')->pluck('description', 'id')->prepend('', '');
    }

    public static function dropdownFormatWithBreeds()
    {
        return self::with('breeds')
            ->orderBy('description')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->description => $item->breeds->pluck('description', 'id')->toArray()];
            })
            ->prepend('', '');
    }
}
