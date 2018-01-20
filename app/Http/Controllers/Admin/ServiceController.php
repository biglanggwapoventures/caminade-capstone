<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Common\CRUDController;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceController extends CRUDController
{
    public function __construct(Service $model, Request $request)
    {
        $this->middleware('role:admin', ['except' => ['index']]);
        parent::__construct();
        $this->resourceModel = $model;
        $this->validationRules = [
            'store' => [
                'name' => ['required', Rule::unique($model->getTable())],
                'description' => ['present'],
                'duration' => ['required', 'integer'],
                'service_status' => ['required', Rule::in(['active', 'inactive'])],
                'price' => ['required', 'numeric'],
            ],
            'update' => [
                'name' => ['required', Rule::unique($model->getTable())->ignore($request->route('service'))],
                'description' => ['present'],
                'duration' => ['required', 'integer'],
                'price' => ['required', 'numeric'],
                'service_status' => ['required', Rule::in(['active', 'inactive'])],
            ],
        ];
    }

}
