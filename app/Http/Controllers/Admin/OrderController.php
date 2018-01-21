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

    protected $filterFields = [
        'from' => ['order_date', '>='],
        'to' => ['order_date', '<='],
    ];

    public function __construct(Order $model, Product $product, OrderLine $line, User $customer)
    {
        parent::__construct();
        $this->resourceModel = $model;
        $this->relatedModel = 'line';
        $this->validationRules = [
            'store' => [
                'order_type' => ['required', Rule::in('WALK_IN', 'IN_HOUSE')],
                'parent.customer_id' => ['nullable', 'required_if:order_type,IN_HOUSE', Rule::exists($customer->getTable(), $customer->getKeyName())],
                'parent.customer_name' => 'required_if:order_type,WALK_IN',
                'parent.remarks' => ['present', 'nullable'],
                'child.*.product_id' => ['required', 'distinct', Rule::exists($product->getTable(), $product->getKeyName())],
                'child.*.stock' => ['required', 'numeric'],
                // 'child.*.quantity' => ['required', 'numeric', 'max:child.*.stock'],
                'child.*.unit_price' => ['required', 'numeric'],
                'child.*.discount' => ['nullable', 'numeric'],
            ],
            'update' => [
                'order_type' => ['required', Rule::in('WALK_IN', 'IN_HOUSE')],
                'parent.customer_id' => ['nullable', 'required_if:order_type,IN_HOUSE', Rule::exists($customer->getTable(), $customer->getKeyName())],
                'parent.customer_name' => 'required_if:order_type,WALK_IN',
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
        collect($this->filterFields)->each(function ($value, $key) use ($query) {
            if ($filter = request()->{$key}) {
                list($column, $operand) = $value;
                $query->where($column, $operand, $filter);
            }
        });
        if ($name = request()->customer_name) {
            $query->withCustomerName($name);
        }
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

    public function beforeStore()
    {
        $this->validatedInput['parent']['order_date'] = date('Y-m-d');
        if ($this->validatedInput['order_type'] === 'WALK_IN') {
            $this->validatedInput['parent']['customer_id'] = null;
        } else {
            $this->validatedInput['parent']['customer_name'] = null;
        }
    }

    public function beforeUpdate()
    {
        if ($this->validatedInput['order_type'] === 'WALK_IN') {
            $this->validatedInput['parent']['customer_id'] = null;
        } else {
            $this->validatedInput['parent']['customer_name'] = null;
        }
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
