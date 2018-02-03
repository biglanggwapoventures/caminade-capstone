<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Common\CRUDController;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Toast;

class SupplierController extends CRUDController
{
    public function __construct(Supplier $model, Request $request)
    {
        parent::__construct();
        $this->resourceModel = $model;
        $this->validationRules = [
            'store' => [
                'description' => ['required', Rule::unique($model->getTable())],
            ],
            'update' => [
                'description' => ['required', Rule::unique($model->getTable())->ignore($request->route('supplier'))],
            ],
        ];
    }

    public function afterStore($category)
    {
        Toast::success('New product supplier has been added!');
    }

    public function afterUpdate($category)
    {
        Toast::success('Product supplier has been successfully updated!');
    }
}
