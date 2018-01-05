<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Common\CRUDController;
use App\PetBreed;
use App\PetCategory;
use Illuminate\Validation\Rule;

class PetBreedController extends CRUDController
{
    public function __construct(PetBreed $model)
    {
        parent::__construct();
        $this->resourceModel = $model;
        $this->validationRules = [
            'store' => [
                'description' => ['required', Rule::unique($model->getTable())],
                'pet_category_id' => ['required', Rule::exists('pet_categories', 'id')],
            ],
            'update' => [
                'description' => ['required', Rule::unique($model->getTable())->ignore(request()->route('pet_breed'))],
                'pet_category_id' => ['required', Rule::exists('pet_categories', 'id')],
            ],
        ];
    }

    public function beforeIndex($query)
    {
        $query->with('category');
    }

    public function beforeCreate()
    {
        $this->viewData['categories'] = PetCategory::dropdownFormat();
    }

    public function beforeEdit($model)
    {
        $this->viewData['categories'] = PetCategory::dropdownFormat();
    }
}
