<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Common\CRUDController;
use App\Pet;
use App\PetCategory;
use App\PetReproductiveAlteration;
use App\User;
use Illuminate\Validation\Rule;
use Toast;

class PetController extends CRUDController
{
    public function __construct(Pet $model)
    {
        $this->middleware('role:admin,staff', ['except' => ['show', 'index']]);
        $this->middleware('role:admin,staff,doctor', ['only' => ['show', 'index']]);
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
                'color' => 'required',
                'weight' => 'nullable|numeric',
                'physical_characteristics' => 'nullable',
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
                'color' => 'required',
                'weight' => 'nullable|numeric',
                'physical_characteristics' => 'nullable',
            ],
        ];
    }

    public function beforeIndex($query)
    {
        $query->when(($customer = request()->customer_id), function ($query) use ($customer) {
            return $query->ownedBy($customer);
        });

        $query->when(($name = trim(request()->pet_name)), function ($query) use ($name) {
            return $query->where('name', 'like', "%{$name}%");
        });

        $this->viewData['customerList'] = User::customerList()->prepend('** ALL CUSTOMER **', '');
        $query->with('owner');
    }

    public function beforeCreate()
    {
        $this->viewData['reproductiveAlterations'] = PetReproductiveAlteration::dropdownFormat();
        $this->viewData['breeds'] = PetCategory::dropdownFormatWithBreeds();
        $this->viewData['customers'] = User::customerList();
    }

    public function beforeShow($model)
    {
        $model;
    }

    public function beforeEdit($model)
    {
        $this->beforeCreate();
    }

    public function afterStore($model)
    {
        Toast::success('New pet has been added!');
    }

    public function afterUpdate($model)
    {
        Toast::success('Pet has been successfully updated!');
    }

}
