<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Common\CRUDController;
use App\Pet;
use App\PetBreed;
use App\PetCategory;
use App\PetReproductiveAlteration;
use Auth;
use Illuminate\Validation\Rule;

class PetController extends CRUDController
{
    public function __construct(Pet $model, PetReproductiveAlteration $alteration, PetBreed $breed)
    {
        parent::__construct();
        $this->resourceModel = $model;
        $this->validationRules = [
            'store' => [
                'name' => ['required', 'max:120'],
                'birthdate' => ['nullable', 'date'],
                'gender' => ['required', 'in:MALE,FEMALE'],
                'pet_breed_id' => ['required', Rule::exists($breed->getTable(), 'id')],
                'pet_reproductive_alteration_id' => ['required', Rule::exists($alteration->getTable(), 'id')],
            ],
            'update' => [
                'name' => ['required', 'max:120'],
                'birthdate' => ['nullable', 'date'],
                'gender' => ['required', 'in:MALE,FEMALE'],
                'pet_breed_id' => ['required', Rule::exists('pet_breeds', 'id')],
                'pet_reproductive_alteration_id' => ['required', Rule::exists('pet_reproductive_alterations', 'id')],
            ],
        ];
    }

    public function beforeIndex($query)
    {
        $query->whereUserId(Auth::id());
        $this->viewData['breeds'] = PetCategory::dropdownFormatWithBreeds();
        $this->viewData['reproductiveAlterations'] = PetReproductiveAlteration::dropdownFormat();
    }

    public function beforeStore()
    {
        $this->validatedInput['user_id'] = Auth::id();
    }

    public function beforeCreate()
    {
        $this->viewData['reproductiveAlterations'] = PetReproductiveAlteration::dropdownFormat();
        $this->viewData['breeds'] = PetCategory::dropdownFormatWithBreeds();
    }

    public function beforeEdit($model)
    {
        $this->beforeCreate();
    }
}
