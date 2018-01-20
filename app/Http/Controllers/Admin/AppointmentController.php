<?php

namespace App\Http\Controllers\Admin;

use App\Appointment;
use App\AppointmentFinding;
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
        AppointmentProduct $usedProduct,
        AppointmentFinding $finding
    ) {
        parent::__construct();
        $this->resourceModel = $model;
        $this->relatedModel = 'line';
        $this->validationRules = [
            'store' => [
                'parent.appointment_date' => 'required|date_format:Y-m-d',
                'parent.appointment_time' => 'required|date_format:H:i',
                'parent.customer_id' => ['required', new CustomerRole],
                'parent.doctor_id' => ['nullable', 'required_if:parent.appointment_status,APPROVED', new DoctorRole],
                'parent.remarks' => 'present',
                'parent.appointment_status' => ['required', Rule::in(['PENDING', 'APPROVED', 'DENIED', 'COMPLETED'])],
                'parent.status_remarks' => ['nullable', 'required_if:parent.appointment_status,DENIED'],
                'parent.is_completed' => ['sometimes', 'boolean'],
                'child.*.pet_id' => ['required', Rule::exists($pet->getTable(), $pet->getKeyName())],
                'child.*.service_id' => ['required', Rule::exists($service->getTable(), $service->getKeyName())],
                'products.*.product_id' => ['nullable', 'required_with:products.*.quantity', Rule::exists($product->getTable(), $pet->getKeyName())],
                'products.*.quantity' => ['nullable', 'required_with:products.*.product_id', 'numeric'],

                'findings.*.pet_id' => ['nullable', 'distinct', 'required_with:findings.*.findings', Rule::exists($pet->getTable(), $pet->getKeyName())],
                'findings.*.findings' => ['nullable', 'required_with:products.*.pet_id'],
            ],
            'update' => [
                'parent.appointment_date' => 'required|date_format:Y-m-d',
                'parent.appointment_time' => 'required|date_format:H:i',
                'parent.customer_id' => ['required', new CustomerRole],
                'parent.doctor_id' => ['nullable', 'required_if:parent.appointment_status,APPROVED', new DoctorRole],
                'parent.remarks' => 'present',
                'parent.appointment_status' => ['required', Rule::in(['PENDING', 'APPROVED', 'DENIED', 'COMPLETED'])],
                'parent.status_remarks' => ['nullable', 'required_if:parent.appointment_status,DENIED'],
                'parent.is_completed' => ['sometimes', 'boolean'],
                'child.*.id' => ['sometimes', Rule::exists($line->getTable())],
                'child.*.pet_id' => ['required', Rule::exists($pet->getTable(), $pet->getKeyName())],
                'child.*.service_id' => ['required', Rule::exists($service->getTable(), $service->getKeyName())],
                'products.*.id' => ['sometimes', Rule::exists($usedProduct->getTable())],
                'products.*.product_id' => ['nullable', 'required_with:products.*.quantity', Rule::exists($product->getTable(), $pet->getKeyName())],
                'products.*.quantity' => ['nullable', 'required_with:products.*.product_id', 'numeric'],
                'findings.*.id' => ['sometimes', Rule::exists($finding->getTable())],
                'findings.*.pet_id' => ['nullable', 'distinct', 'required_with:findings.*.findings', Rule::exists($pet->getTable(), $pet->getKeyName())],
                'findings.*.findings' => ['nullable', 'required_with:products.*.pet_id'],
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

    public function beforeStore()
    {
        if ($this->validatedInput['parent']['appointment_status'] === 'COMPLETED') {
            $this->validatedInput['parent']['appointment_status'] = 'APPROVED';
            $this->validatedInput['parent']['completed_at'] = now()->format('Y-m-d H:i:s');
        }
    }

    public function beforeUpdate()
    {
        $this->beforeStore();
    }

    public function afterStore($model)
    {
        $this->createRelations($model, 'products', 'usedProducts');
        $this->createRelations($model, 'findings', 'findings');
    }

    public function afterUpdate($model)
    {
        $this->updateParentRelations($model, 'products', 'usedProducts');
        $this->updateParentRelations($model, 'findings', 'findings');
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

    public function beforeEdit($model)
    {
        $this->beforeCreate();

        $model->load(['line.service', 'usedProducts.product', 'findings']);
        $this->viewData['customerPets'] = Pet::with('breed')->ownedBy($model->customer_id)->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => "{$item->name} ({$item->breed->description})"];
            })
            ->prepend('', '');
    }
}
