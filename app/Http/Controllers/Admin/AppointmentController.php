<?php

namespace App\Http\Controllers\Admin;

use App\Appointment;
use App\AppointmentLine;
use App\AppointmentProduct;
use App\Http\Controllers\Common\CRUDController;
use App\Pet;
use App\Product;
use App\Rules\CustomerRole;
use App\Rules\DoctorRole;
use App\Service;
use App\User;
use Illuminate\Validation\Rule;

class AppointmentController extends CRUDController
{
    protected $filterFields = [
        'from' => ['appointment_date', '>='],
        'to' => ['appointment_date', '<='],
        'customer_id' => ['customer_id', '='],
        'status' => ['appointment_status', '='],
    ];

    public function __construct(
        Appointment $model,
        Pet $pet,
        Service $service,
        User $customer,
        Product $product,
        AppointmentLine $line,
        AppointmentProduct $usedProduct
    ) {
        parent::__construct();
        $this->resourceModel = $model;
        $this->relatedModel = 'line';
        $this->validationRules = [
            'store' => [
                'parent.appointment_date' => 'required|date_format:Y-m-d',
                'parent.appointment_time' => 'required|date_format:H:i',
                'parent.customer_id' => ['required', new CustomerRole],
                'parent.doctor_id' => ['nullable', 'present', new DoctorRole],
                'parent.remarks' => 'present',
                'parent.findings' => 'present',
                'parent.appointment_status' => ['required', Rule::in(['PENDING', 'APPROVED', 'DENIED'])],
                'parent.status_remarks' => ['present'],
                'child.*.pet_id' => ['required', Rule::exists($pet->getTable(), $pet->getKeyName())],
                'child.*.service_id' => ['required', Rule::exists($service->getTable(), $service->getKeyName())],
                'products.*.product_id' => ['nullable', 'required_with:products.*.quantity', Rule::exists($product->getTable(), $pet->getKeyName())],
                'products.*.quantity' => ['nullable', 'required_with:products.*.product_id', 'numeric'],
            ],
            'update' => [
                'parent.appointment_date' => 'required|date_format:Y-m-d',
                'parent.appointment_time' => 'required|date_format:H:i:s',
                'parent.customer_id' => ['required', new CustomerRole],
                'parent.doctor_id' => ['nullable', 'present', new DoctorRole],
                'parent.remarks' => 'present',
                'parent.findings' => 'present',
                'parent.appointment_status' => ['required', Rule::in(['PENDING', 'APPROVED', 'DENIED'])],
                'parent.status_remarks' => ['present'],
                'child.*.id' => ['sometimes', Rule::exists($line->getTable())],
                'child.*.pet_id' => ['required', Rule::exists($pet->getTable(), $pet->getKeyName())],
                'child.*.service_id' => ['required', Rule::exists($service->getTable(), $service->getKeyName())],
                'products.*.id' => ['sometimes', Rule::exists($usedProduct->getTable())],
                'products.*.product_id' => ['nullable', 'required_with:products.*.quantity', Rule::exists($product->getTable(), $pet->getKeyName())],
                'products.*.quantity' => ['nullable', 'required_with:products.*.product_id', 'numeric'],
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
        $this->viewData['customerList'] = User::customerList()->prepend('** ALL CUSTOMER **', '');
    }

    public function beforeCreate()
    {
        $services = Service::select('id', 'name', 'price', 'duration')->orderBy('name')->get();
        $products = Product::select('id', 'name', 'price')->orderBy('name')->get();

        $this->viewData['customerList'] = User::customerList()->prepend('', '');

        $this->viewData['doctorList'] = User::ofRole('DOCTOR')
            ->get()
            ->pluck('fullname', 'id')
            ->prepend('', '');

        $this->viewData['serviceList'] = $services->pluck('name', 'id')->prepend('', '');
        $this->viewData['productList'] = $products->pluck('name', 'id')->prepend('', '');

        $this->viewData['serviceInfo'] = $services->keyBy('id');
        $this->viewData['productInfo'] = $products->keyBy('id');
    }

    public function beforeStore()
    {
        $this->validatedInput['appointment_status'] = 'APPROVED';
    }

    public function beforeUpdate()
    {
        $this->validatedInput['appointment_status'] = 'APPROVED';
    }

    public function afterStore($model)
    {
        if (!empty(array_filter(array_column(request()->products, 'product_id')))) {
            $this->createRelations($model, 'products', 'usedProducts');
        }
    }

    public function afterUpdate($model)
    {
        if (!empty(array_filter(array_column(request()->products, 'product_id')))) {
            $this->updateParentRelations($model, 'products', 'usedProducts');
        }
    }

    public function beforeEdit($model)
    {
        $this->beforeCreate();

        $model->load(['line.service', 'usedProducts.product']);
        $this->viewData['customerPets'] = Pet::with('breed')->ownedBy($model->customer_id)->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => "{$item->name} ({$item->breed->description})"];
            })
            ->prepend('', '');
    }
}
