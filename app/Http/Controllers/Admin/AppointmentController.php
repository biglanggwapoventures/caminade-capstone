<?php

namespace App\Http\Controllers\Admin;

use App\Appointment;
use App\AppointmentFinding;
use App\AppointmentLine;
use App\AppointmentProduct;
use App\Http\Controllers\Common\CRUDController;
use App\Pet;
use App\PetLog;
use App\Product;
use App\Rules\CustomerRole;
use App\Rules\DoctorRole;
use App\Service;
use App\User;
use Illuminate\Validation\Rule;
use Toast;

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
        AppointmentFinding $finding,
        PetLog $petLog
    ) {
        parent::__construct();
        $this->resourceModel = $model;
        $this->relatedModel = 'line';
        $this->validationRules = [
            'store' => [
                'parent.appointment_date' => 'required|date_format:Y-m-d|after_or_equal:today',
                'parent.appointment_time' => 'required|date_format:H:i:s',
                'parent.customer_id' => ['required', new CustomerRole],
                'parent.doctor_id' => ['nullable', 'required_if:parent.appointment_status,APPROVED', new DoctorRole],
                'parent.remarks' => 'present',
                'parent.appointment_status' => ['required', Rule::in(['PENDING', 'APPROVED', 'DENIED', 'COMPLETED', 'CANCELLED'])],
                'parent.status_remarks' => ['nullable', 'required_if:parent.appointment_status,DENIED'],
                'parent.is_completed' => ['sometimes', 'boolean'],
                'child.*.pet_id' => ['required', Rule::exists($pet->getTable(), $pet->getKeyName())],
                'child.*.service_id' => ['required', Rule::exists($service->getTable(), $service->getKeyName())],
                'products.*.product_id' => ['nullable', 'required_with:products.*.quantity', Rule::exists($product->getTable(), $pet->getKeyName())],
                'products.*.quantity' => ['nullable', 'required_with:products.*.product_id', 'numeric'],
                'products.*.unit_price' => ['nullable', 'required_with:products.*.product_id', 'numeric'],
                'findings.*.pet_id' => ['nullable', 'distinct', 'required_with:findings.*.findings', Rule::exists($pet->getTable(), $pet->getKeyName())],
                'findings.*.findings' => ['nullable', 'required_with:products.*.pet_id'],

                // 'pet_logs.*.pet_id' => ['nullable', 'required_with:pet_logs.*.log_date,pet_logs.*.log_time,pet_logs.*.remarks', Rule::exists($pet->getTable(), $pet->getKeyName())],
                // 'pet_logs.*.log_date' => ['nullable', 'required_with:pet_logs.*.pet_id,pet_logs.*.log_time,pet_logs.*.remarks', 'date'],
                // 'pet_logs.*.log_time' => ['nullable', 'required_with:pet_logs.*.pet_id,pet_logs.*.log_date,pet_logs.*.remarks', 'date_format:H:i'],
                // 'pet_logs.*.remarks' => ['nullable', 'required_with:pet_logs.*.pet_id,pet_logs.*.log_date,pet_logs.*.log_time'],
            ],
            'update' => [
                'parent.appointment_date' => 'required|date_format:Y-m-d',
                'parent.appointment_time' => 'required|date_format:H:i:s',
                'parent.customer_id' => ['required', new CustomerRole],
                'parent.doctor_id' => ['nullable', 'required_if:parent.appointment_status,APPROVED', new DoctorRole],
                'parent.remarks' => 'present',
                'parent.appointment_status' => ['required', Rule::in(['PENDING', 'APPROVED', 'DENIED', 'COMPLETED', 'CANCELLED'])],
                'parent.status_remarks' => ['nullable', 'required_if:parent.appointment_status,DENIED'],
                'parent.is_completed' => ['sometimes', 'boolean'],
                'child.*.id' => ['sometimes', Rule::exists($line->getTable())],
                'child.*.pet_id' => ['required', Rule::exists($pet->getTable(), $pet->getKeyName())],
                'child.*.service_id' => ['required', Rule::exists($service->getTable(), $service->getKeyName())],
                'child.*.service_price' => ['required', 'required_with:child.*.service_id', 'numeric'],
                'child.*.service_duration' => ['required', 'required_with:child.*.service_id', 'numeric'],
                'products.*.id' => ['sometimes', Rule::exists($usedProduct->getTable())],
                'products.*.product_id' => ['nullable', 'required_with:products.*.quantity', Rule::exists($product->getTable(), $pet->getKeyName())],
                'products.*.quantity' => ['nullable', 'required_with:products.*.product_id', 'numeric'],
                'products.*.unit_price' => ['nullable', 'required_with:products.*.product_id', 'numeric'],
                'findings.*.id' => ['sometimes', Rule::exists($finding->getTable())],
                'findings.*.pet_id' => ['nullable', 'distinct', 'required_with:findings.*.findings', Rule::exists($pet->getTable(), $pet->getKeyName())],
                'findings.*.findings' => ['nullable', 'required_with:products.*.pet_id'],
                // 'pet_logs.*.id' => ['sometimes', Rule::exists($petLog->getTable(), $petLog->getKeyName())],
                // 'pet_logs.*.pet_id' => ['nullable', 'required_with:pet_logs.*.log_date,pet_logs.*.log_time,pet_logs.*.remarks', Rule::exists($pet->getTable(), $pet->getKeyName())],
                // 'pet_logs.*.log_date' => ['nullable', 'required_with:pet_logs.*.pet_id,pet_logs.*.log_time,pet_logs.*.remarks', 'date'],
                // 'pet_logs.*.log_time' => ['nullable', 'required_with:pet_logs.*.pet_id,pet_logs.*.log_date,pet_logs.*.remarks', 'date_format:H:i'],
                // 'pet_logs.*.remarks' => ['nullable', 'required_with:pet_logs.*.pet_id,pet_logs.*.log_date,pet_logs.*.log_time'],
            ],
        ];
    }

    public function beforeIndex($query)
    {
        // session()->flash('SMS', ['result' => 'success', 'message' => 'SMS has been sent succesfully!']);
        $query->with(['line', 'usedProducts', 'boarding.productsUsed']);
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
        if($model->appointment_status == 'COMPLETED'){
            $this->createRelations($model, 'products', 'usedProducts');
            $this->createRelations($model, 'findings', 'findings');
            // $this->createRelations($model, 'pet_logs', 'petLogs');
            $model->usedProducts->each->saveProductLog();
        }
        Toast::success('New appointment has been added!');
    }

    public function afterUpdate($model)
    {
        if($model->appointment_status == 'COMPLETED'){
            $this->updateParentRelations($model, 'products', 'usedProducts');
            $this->updateParentRelations($model, 'findings', 'findings');
            // $this->updateParentRelations($model, 'pet_logs', 'petLogs');
            $model->usedProducts->each->saveProductLog();
        }
        Toast::success('Appointment has been successfully updated!');
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

        $this->viewData['statusOptions'] = $this->getStatusOptions('pending');
    }

    public function beforeEdit($model)
    {
        $this->beforeCreate();

        $model->load(['line.service', 'usedProducts.product', 'findings', 'petLogs', 'boarding.productsUsed']);
        $this->viewData['customerPets'] = Pet::with('breed')->ownedBy($model->customer_id)->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => "{$item->name} ({$item->breed->description})"];
            })
            ->prepend('', '');
        $this->viewData['statusOptions'] = $this->getStatusOptions($model->appointment_status);
    }

    private function getStatusOptions($currentStatus)
    {
        switch (strtolower($currentStatus)) {
            case 'approved':
                return ['APPROVED' => 'Approved', 'COMPLETED' => 'Completed'];
            case 'completed':
                return ['COMPLETED' => 'Completed'];
            case 'cancelled':
                return ['CANCELLED' => 'Cancelled'];
            default:
                return ['PENDING' => '', 'DENIED' => 'Declined', 'APPROVED' => 'Approved', 'COMPLETED' => 'Completed'];
        }
    }
}
