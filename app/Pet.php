<?php

namespace App;

use App\PetBreed;
use App\PetCategory;
use App\PetReproductiveAlteration;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $fillable = [
        'user_id',
        'pet_category_id',
        'pet_breed_id',
        'pet_reproductive_alteration_id',
        'name',
        'birthdate',
        'gender',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function breed()
    {
        return $this->belongsTo(PetBreed::class, 'pet_breed_id');
    }

    public function category()
    {
        return $this->belongsTo(PetCategory::class);
    }

    public function reproductiveAlteration()
    {
        return $this->belongsTo(PetReproductiveAlteration::class, 'pet_reproductive_alteration_id');
    }

    public function scopeFieldsForMasterList($query)
    {
        return $query->with(['breed.category', 'reproductiveAlteration']);
    }

    public function scopeOwnedBy($query, $userId)
    {
        return $query->whereUserId($userId);
    }

}
