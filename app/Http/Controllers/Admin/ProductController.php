<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Common\CRUDController;
use App\Product;
use App\ProductCategory;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Toast;

class ProductController extends CRUDController
{
    protected $filterFields = [
        'name' => ['name', 'like', '%_%'],
        'category' => ['product_category_id', '='],
        'supplier' => ['supplier_id', '='],
        'status' => ['product_status', '='],
    ];

    public function __construct(Product $model, Request $request, ProductCategory $category, Supplier $supplier)
    {
        $this->middleware('role:admin', ['except' => ['index']]);
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
                'product_status' => ['required', Rule::in(['active', 'inactive'])],
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
                'product_status' => ['required', Rule::in(['active', 'inactive'])],
                'photo' => ['sometimes', 'image', 'dimensions:max_width=4000,max_height=4000'],
            ],
        ];
    }

    public function beforeIndex($query)
    {
        $this->viewData['suppliers'] = Supplier::dropdownFormat();
        $this->viewData['categories'] = ProductCategory::dropdownFormat();

        collect($this->filterFields)->each(function ($value, $key) use ($query) {
            if ($filter = request()->{$key}) {
                list($column, $operand) = $value;
                $filter = isset($value[2]) ? str_replace('_', $filter, $value[2]) : $filter;
                $query->where($column, $operand, $filter);
            }
        });
    }

    public function afterIndex($collection)
    {
        switch (request()->sort_by) {
            case 'stock_desc':
                return $collection->sortByDesc('stock_on_hand');
            case 'stock_asc':
                return $collection->sortBy('stock_on_hand');
            default:
                return $collection;
        }
    }

    public function beforeCreate()
    {
        $this->viewData['suppliers'] = Supplier::dropdownFormat();
        $this->viewData['categories'] = ProductCategory::dropdownFormat();
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

    public function afterStore($product)
    {
        $product->setBeginningBalance();
        Toast::success('New product has been added!');
    }

    public function afterUpdate($product)
    {
        $product->setBeginningBalance();
        Toast::success('Product has been added!');
    }
}
