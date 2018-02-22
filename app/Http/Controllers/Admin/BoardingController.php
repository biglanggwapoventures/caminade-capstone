<?php

namespace App\Http\Controllers\Admin;

use App\Appointment;
use App\Boarding;
use App\BoardingPetLog;
use App\BoardingProductsUsed;
use App\Http\Controllers\Common\CRUDController;
use App\Pet;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BoardingController extends CRUDController
{
    private $request;
    private $appointmentId;

    public function __construct(
        Request $request,
        Boarding $model,
        Appointment $appointment,
        Product $product,
        Pet $pet,
        BoardingProductsUsed $usedProduct,
        BoardingPetLog $petLog
    ) {
        parent::__construct();
        $this->request = $request;
        $this->resourceModel = $model;
        $this->validationRules = [
            'store' => [
                'appointment_id' => ['required', Rule::exists($appointment->getTable(), $appointment->getKeyName())],
                'timestamp_in' => ['required', 'date_format:"m/d/Y h:i A"'],
                'timestamp_out' => ['nullable', 'date_format:"m/d/Y h:i A"'],
                'price_per_day' => ['required', 'numeric', 'min:0'],

                'products' => ['required', 'array'],
                'products.*.product_id' => ['nullable', Rule::exists($product->getTable(), $product->getKeyName())],
                'products.*.unit_price' => ['nullable', 'required_with:products.*.product_id', 'numeric', 'min:0'],
                'products.*.discount' => ['nullable', 'numeric', 'min:0'],
                'products.*.quantity' => ['nullable', 'required_with:products.*.product_id', 'numeric', 'min:0'],
                'products.*.date_used' => ['nullable', 'required_with:products.*.product_id', 'date'],
                'products.*.time_used' => ['nullable', 'required_with:products.*.product_id', 'date_format:H:i'],

                'pet_logs' => ['nullable', 'array'],
                'pet_logs.*.pet_id' => ['nullable', Rule::exists($pet->getTable(), $pet->getKeyName())],
                'pet_logs.*.log_date' => ['required_with:pet_logs.*.pet_id', 'date'],
                'pet_logs.*.log_time' => ['required_with:pet_logs.*.pet_id', 'date_format:H:i'],
                'pet_logs.*.remarks' => ['required_with:pet_logs.*.pet_id'],
            ],
            'update' => [
                'appointment_id' => ['required', Rule::exists($appointment->getTable(), $appointment->getKeyName())],
                'timestamp_in' => ['required', 'date_format:"m/d/Y h:i A"'],
                'timestamp_out' => ['nullable', 'date_format:"m/d/Y h:i A"'],
                'price_per_day' => ['required', 'numeric', 'min:0'],

                'products' => ['required', 'array'],
                'products.*.id' => ['sometimes', Rule::exists($usedProduct->getTable(), $usedProduct->getKeyName())],
                'products.*.product_id' => ['nullable', Rule::exists($product->getTable(), $product->getKeyName())],
                'products.*.unit_price' => ['nullable', 'required_with:products.*.product_id', 'numeric', 'min:0'],
                'products.*.discount' => ['nullable', 'numeric', 'min:0'],
                'products.*.quantity' => ['nullable', 'required_with:products.*.product_id', 'numeric', 'min:0'],
                'products.*.date_used' => ['nullable', 'required_with:products.*.product_id', 'date'],
                'products.*.time_used' => ['nullable', 'required_with:products.*.product_id', 'date_format:H:i'],

                'pet_logs' => ['nullable', 'array'],
                'pet_logs.*.id' => ['sometimes', Rule::exists($petLog->getTable(), $petLog->getKeyName())],
                'pet_logs.*.pet_id' => ['nullable', Rule::exists($pet->getTable(), $pet->getKeyName())],
                'pet_logs.*.log_date' => ['required_with:pet_logs.*.pet_id', 'date'],
                'pet_logs.*.log_time' => ['required_with:pet_logs.*.pet_id', 'date_format:H:i'],
                'pet_logs.*.remarks' => ['required_with:pet_logs.*.pet_id'],
            ],
        ];
    }

    private function setViewData()
    {
        $products = Product::select('id', 'name', 'price')->orderBy('name')->get();
        $appointment = Appointment::with('customer.pets.breed')->find($this->appointmentId);

        $this->viewData['productList'] = $products->pluck('name', 'id')->prepend('', '');
        $this->viewData['productInfo'] = $products->keyBy('id');

        $this->viewData['customerPets'] = data_get($appointment, 'customer.pets')->mapWithKeys(function ($item) {
            return [$item->id => "{$item->name} ({$item->breed->description})"];
        });
    }

    public function beforeIndex($query)
    {
        return $query->with(['productsUsed', 'appointment.customer']);
    }

    public function beforeCreate()
    {
        $this->appointmentId = $this->request->input('appointment-id');
        $this->setViewData();
    }

    public function beforeEdit($model)
    {
        $this->appointmentId = $model->appointment_id;
        $this->setViewData();
    }

    public function beforeStore()
    {
        $this->validatedInput['timestamp_in'] = date_create_from_format('m/d/Y h:i A', $this->request->timestamp_in)->format('Y-m-d H:i:s');
        $this->validatedInput['timestamp_out'] = $this->request->timestamp_out ? date_create_from_format('m/d/Y h:i A', $this->request->timestamp_out)->format('Y-m-d H:i:s') : null;
    }

    public function afterStore($model)
    {
        $this->createRelations($model, 'products', 'productsUsed');
        $this->createRelations($model, 'pet_logs', 'petJournals');
        $model->productsUsed->each->saveProductLog();
    }

    public function beforeUpdate()
    {
        $this->beforeStore();
    }

    public function afterUpdate($model)
    {
        $this->updateParentRelations($model, 'products', 'productsUsed');
        $this->updateParentRelations($model, 'pet_logs', 'petJournals');
        $model->productsUsed->each->saveProductLog();
    }
}
