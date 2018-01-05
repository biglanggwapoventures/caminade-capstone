<?php

namespace App;

use App\Pet;
use Illuminate\Database\Eloquent\Model;

class PetReproductiveAlteration extends Model
{
    protected $fillable = [
        'description',
    ];

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    public function scopeFieldsForMasterList($query)
    {
        return $query;
    }

    public static function dropdownFormat()
    {
        return self::orderBy('description')->pluck('description', 'id')->prepend('', '');
    }
}
