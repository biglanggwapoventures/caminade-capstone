<?php

namespace App\Http\Controllers\User;

use App\Appointment;
use App\Http\Controllers\Common\CRUDController;
use App\Pet;
use App\Service;
use Auth;
use Illuminate\Validation\Rule;
use Toast;

class AppointmentController extends CRUDController
{
    public function __construct(Appointment $model, Pet $pet, Service $service)
    {
        parent::__construct();
        $this->resourceModel = $model;
        $this->relatedModel = 'line';
        $this->validationRules = [
            'store' => [
                'parent.appointment_date' => 'required|date_format:Y-m-d',
                'parent.appointment_time' => 'required|date_format:H:i',
                'parent.remarks' => 'present',
                'child.*.pet_id' => [
                    'required',
                    Rule::exists($pet->getTable(), $pet->getKeyName())->where(function ($query) {
                        $query->whereUserId(Auth::id());
                    }),
                ],
                'child.*.service_id' => [
                    'required',
                    Rule::exists($service->getTable(), $service->getKeyName()),
                ],
            ],
            'update' => [
                'parent.appointment_date' => 'required|date_format:Y-m-d',
                'parent.appointment_time' => 'required|date_format:H:i',
                'parent.remarks' => 'present',
                'child.*.pet_id' => 'sometimes',
                'child.*.pet_id' => [
                    'required',
                    Rule::exists($pet->getTable(), $pet->getKeyName())->where(function ($query) {
                        $query->whereUserId(Auth::id());
                    }),
                ],
                'child.*.service_id' => [
                    'required',
                    Rule::exists($service->getTable(), $service->getKeyName()),
                ],
            ],
        ];
    }

    public function beforeIndex($query)
    {
        $query->ofCustomer(Auth::id());
    }

    public function beforeCreate()
    {
        Auth::user()->load('pets.breed');
        $services = Service::active()->orderBy('name')->get(['id', 'name', 'price', 'duration']);

        $this->viewData['pets'] = Auth::user()->pets->mapWithKeys(function ($item) {
            return [$item->id => "{$item->name} ({$item->breed->description})"];
        })->prepend('', '');
        $this->viewData['serviceList'] = $services->pluck('name', 'id')->prepend('', '');
        $this->viewData['serviceInfo'] = $services->keyBy('id');
    }

    public function beforeStore()
    {
        $this->validatedInput['parent']['customer_id'] = Auth::id();
    }

    public function beforeEdit($model)
    {
        $this->beforeCreate();
    }

    public function beforeShow($model)
    {
        $model->load(['line.service', 'line.pet.breed', 'doctor', 'usedProducts.product', 'findings']);
    }

    public function afterStore($model)
    {
        Toast::success('Your appointment has been submitted. We will get back to you as soon as possible!');
    }

    public function afterUpdate($model)
    {
        Toast::success('Appointment has been successfully updated!');
    }
}
