<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Common\CRUDController;
use App\PetReproductiveAlteration;
use Illuminate\Validation\Rule;
use Toast;

class PetReproductiveAlterationController extends CRUDController
{
    public function __construct(PetReproductiveAlteration $model)
    {
        parent::__construct();
        $this->resourceModel = $model;
        $this->validationRules = [
            'store' => [
                'description' => ['required', Rule::unique($model->getTable())],
                'gender' => ['nullable', Rule::in(['MALE', 'FEMALE'])],
            ],
            'update' => [
                'description' => ['required', Rule::unique($model->getTable())->ignore(request()->route('pet_reproductive_alteration'))],
                'gender' => ['nullable', Rule::in(['MALE', 'FEMALE'])],
            ],
        ];
    }

    public function afterStore($category)
    {
        Toast::success('New pet reproductive alteration has been added!');
    }

    public function afterUpdate($category)
    {
        Toast::success('Pet reproductive alteration has been successfully updated!');
    }
}
