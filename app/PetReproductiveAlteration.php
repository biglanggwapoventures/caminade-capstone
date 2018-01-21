<?php

namespace App;

use App\Pet;
use Illuminate\Database\Eloquent\Model;

class PetReproductiveAlteration extends Model
{
    protected $fillable = [
        'description',
        'gender',
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
        return self::orderBy('description')
            ->get()
            ->groupBy('gender')
            ->mapWithKeys(function ($options, $gender) {
                return [$gender => $options->pluck('description', 'id')];
            })
            ->prepend('', '')
            ->toArray();
    }

    public function getGenderAttribute($val)
    {
        return $val ?: 'N/A';
    }
}
