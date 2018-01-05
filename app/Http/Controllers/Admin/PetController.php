<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Common\CRUDController;
use App\Pet;
use App\PetCategory;
use App\PetReproductiveAlteration;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PetController extends CRUDController
{
    public function __construct(Pet $model)
    {
        parent::__construct();
        $this->resourceModel = $model;
        $this->validationRules = [
            'store' => [
                'name' => ['required', 'max:120'],
                'birthdate' => ['required', 'date'],
                'gender' => ['required', 'in:MALE,FEMALE'],
                'pet_breed_id' => ['required', Rule::exists('pet_breeds', 'id')],
                'pet_reproductive_alteration_id' => ['required', Rule::exists('pet_reproductive_alterations', 'id')],
            ],
            'update' => [
                'name' => ['required', 'max:120'],
                'birthdate' => ['required', 'date'],
                'gender' => ['required', 'in:MALE,FEMALE'],
                'description' => ['required', Rule::unique($model->getTable())->ignore(request()->route('pet_breed'))],
                'pet_breed_id' => ['required', Rule::exists('pet_breeds', 'id')],
                'pet_reproductive_alteration_id' => ['required', Rule::exists('pet_reproductive_alterations', 'id')],
            ],
        ];
    }

    public function beforeStore()
    {
        $this->validatedInput['user_id'] = 1;
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
