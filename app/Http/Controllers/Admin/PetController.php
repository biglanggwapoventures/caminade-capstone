<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Common\CRUDController;
use App\Pet;
use App\PetCategory;
use App\PetReproductiveAlteration;
use App\User;
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
                'user_id' => [
                    'required',
                    Rule::exists('users', 'id')->where(function ($q) {
                        $q->where('role', 'CUSTOMER');
                    }),
                ],
                'birthdate' => ['nullable', 'date'],
                'gender' => ['required', 'in:MALE,FEMALE'],
                'pet_breed_id' => ['required', Rule::exists('pet_breeds', 'id')],
                'pet_reproductive_alteration_id' => ['required', Rule::exists('pet_reproductive_alterations', 'id')],
            ],
            'update' => [
                'name' => ['required', 'max:120'],
                'user_id' => [
                    'required',
                    Rule::exists('users', 'id')->where(function ($q) {
                        $q->where('role', 'CUSTOMER');
                    }),
                ],
                'birthdate' => ['nullable', 'date'],
                'gender' => ['required', 'in:MALE,FEMALE'],
                'pet_breed_id' => ['required', Rule::exists('pet_breeds', 'id')],
                'pet_reproductive_alteration_id' => ['required', Rule::exists('pet_reproductive_alterations', 'id')],
            ],
        ];
    }

    public function beforeIndex($query)
    {
        $query->with('owner');
    }

    public function beforeStore()
    {
        $this->validatedInput['user_id'] = 1;
    }

    public function beforeCreate()
    {
        $this->viewData['reproductiveAlterations'] = PetReproductiveAlteration::dropdownFormat();
        $this->viewData['breeds'] = PetCategory::dropdownFormatWithBreeds();
        $this->viewData['customers'] = User::customerList();
    }

    public function beforeEdit($model)
    {
        $this->beforeCreate();
    }
}
