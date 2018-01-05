<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Common\CRUDController;
use App\PetCategory;
use Illuminate\Validation\Rule;

class PetCategoryController extends CRUDController
{
    public function __construct(PetCategory $model)
    {
        parent::__construct();
        $this->resourceModel = $model;
        $this->validationRules = [
            'store' => [
                'description' => ['required', Rule::unique($model->getTable())],
            ],
            'update' => [
                'description' => ['required', Rule::unique($model->getTable())->ignore(request()->route('pet_category'))],
            ],
        ];
    }
}
