<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Common\CRUDController;
use App\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductCategoryController extends CRUDController
{
    public function __construct(ProductCategory $model, Request $request)
    {
        parent::__construct();
        $this->resourceModel = $model;
        $this->validationRules = [
            'store' => [
                'description' => ['required', Rule::unique($model->getTable())],
            ],
            'update' => [
                'description' => ['required', Rule::unique($model->getTable())->ignore($request->route('product_category'))],
            ],
        ];
    }
}
