<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Common\CRUDController;
use App\Product;
use App\ProductCategory;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends CRUDController
{
    public function __construct(Product $model, Request $request, ProductCategory $category, Supplier $supplier)
    {
        parent::__construct();
        $this->resourceModel = $model;
        $this->validationRules = [
            'store' => [
                'product_category_id' => ['required', Rule::exists($category->getTable(), 'id')],
                'supplier_id' => ['required', Rule::exists($supplier->getTable(), 'id')],
                'name' => ['required', Rule::unique($model->getTable())],
                'code' => ['required', Rule::unique($model->getTable())],
                'description' => ['required'],
                'price' => ['required', 'numeric'],
                'stock' => ['required', 'numeric'],
                'reorder_level' => ['required', 'numeric'],
                'photo' => ['required', 'image', 'dimensions:max_width=4000,max_height=4000'],
            ],
            'update' => [
                'product_category_id' => ['required', Rule::exists($category->getTable(), 'id')],
                'supplier_id' => ['required', Rule::exists($supplier->getTable(), 'id')],
                'name' => ['required', Rule::unique($model->getTable())->ignore($request->route('product'))],
                'code' => ['required', Rule::unique($model->getTable())->ignore($request->route('product'))],
                'description' => ['required'],
                'price' => ['required', 'numeric'],
                'reorder_level' => ['required', 'numeric'],
                'photo' => ['sometimes', 'image', 'dimensions:max_width=4000,max_height=4000'],
            ],
        ];
    }

    public function beforeCreate()
    {
        $this->viewData['categories'] = ProductCategory::select('id', 'description')->orderBy('description')->pluck('description', 'id')->prepend('', '');
        $this->viewData['suppliers'] = Supplier::select('id', 'description')->orderBy('description')->pluck('description', 'id')->prepend('', '');
    }

    public function beforeEdit($model)
    {
        $this->beforeCreate();
    }

    public function beforeStore()
    {
        $path = request()->file('photo')->store(
            "products/{$this->validatedInput['code']}", 'public'
        );
        $this->validatedInput['photo_path'] = $path;
    }

    public function beforeUpdate()
    {
        if (request()->hasFile('photo')) {
            // TODO Remove the old photo from disk
            // Storage::disk('public')->delete('folder_path/file_name.jpg');
            $this->beforeStore();
        }

    }
}
