<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Common\CRUDController;
use App\Order;
use App\OrderLine;
use App\Product;
use App\User;
use Illuminate\Validation\Rule;

class OrderController extends CRUDController
{
    public function __construct(Order $model, Product $product, OrderLine $line, User $customer)
    {
        parent::__construct();
        $this->resourceModel = $model;
        $this->relatedModel = 'line';
        $this->validationRules = [
            'store' => [
                'parent.customer_id' => ['required', Rule::exists($customer->getTable(), $customer->getKeyName())],
                'parent.order_date' => ['required', 'date'],
                'parent.remarks' => ['present', 'nullable'],
                'child.*.product_id' => ['required', 'distinct', Rule::exists($product->getTable(), $product->getKeyName())],
                'child.*.stock' => ['required', 'numeric'],
                // 'child.*.quantity' => ['required', 'numeric', 'max:child.*.stock'],
                'child.*.unit_price' => ['required', 'numeric'],
                'child.*.discount' => ['nullable', 'numeric'],
            ],
            'update' => [
                'parent.customer_id' => ['required', Rule::exists($customer->getTable(), $customer->getKeyName())],
                'parent.order_date' => ['required', 'date'],
                'parent.remarks' => ['present', 'nullable'],
                'child.*.id' => ['sometimes', Rule::exists($line->getTable(), $line->getKeyName())],
                'child.*.product_id' => ['required', 'distinct', Rule::exists($product->getTable(), $product->getKeyName())],
                'child.*.stock' => ['required', 'numeric'],
                // 'child.*.quantity' => ['required', 'numeric', 'max:child.*.stock'],
                'child.*.unit_price' => ['required', 'numeric'],
                'child.*.discount' => ['nullable', 'numeric'],
            ],
        ];
    }

    public function beforeIndex($query)
    {
        $this->viewData['customerList'] = User::customerList()->prepend('', '');
    }

    public function beforeCreate()
    {
        $products = Product::with('logs')->orderBy('name')->get(['id', 'name', 'price']);

        $this->viewData['customerList'] = User::customerList()->prepend('', '');
        $this->viewData['productList'] = $products->pluck('name', 'id')->prepend('', '');
        $this->viewData['productInfo'] = $products->keyBy('id');
    }

    public function beforeEdit($model)
    {
        $this->beforeCreate();
    }

    public function afterStore($order)
    {
        $order->line->each->saveProductLog();
    }

    public function afterUpdate($order)
    {
        $order->line->each->saveProductLog();
    }

}
